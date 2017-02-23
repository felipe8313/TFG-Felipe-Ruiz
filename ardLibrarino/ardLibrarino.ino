#include <hidboot.h>
#include <usbhub.h>
#include <Wire.h>

/////////// Librerías para el módulo WiFi ///////////////
#include "WiFiEsp.h"
#ifndef HAVE_HWSERIAL1
#include "SoftwareSerial.h"
SoftwareSerial Serial1(6, 5); // RX, TX
#endif
////////////////////////////////////////////////////////


// Satisfy the IDE, which needs to see the include statment in the ino too.
#ifdef dobogusinclude
#include <spi4teensy3.h>
#include <SPI.h>
#endif


USB     Usb;
HIDBoot<USB_HID_PROTOCOL_KEYBOARD>    HidKeyboard(&Usb);
String barcode;
char barcodeDigit;
String estadoSitio;
int cont = 0;
int pinRojo = 4;
int pinAzul = 2;
int pinVerde = 3;
unsigned long previousMillis = 0;
unsigned long interval = 3000;
bool lectura = false;
char ssid[] = "JAZZTEL_bcUJ";            // your network SSID (name)
char pass[] = "nhpbrpf4nuag";        // your network password
char server[] = "librarino.ticsur.es";
String id = "M1Z4B1A1";

// Initialize the Ethernet client object
WiFiEspClient client;

class KbdRptParser : public KeyboardReportParser
{
  protected:
    virtual void OnKeyDown	(uint8_t mod, uint8_t key);
    virtual void OnKeyPressed(uint8_t key);
};

void KbdRptParser::OnKeyDown(uint8_t mod, uint8_t key)
{
  uint8_t c = OemToAscii(mod, key);

  if (c)
    OnKeyPressed(c);
}

/* what to do when symbol arrives */
void KbdRptParser::OnKeyPressed(uint8_t key) {

  barcodeDigit = ((char)key); // Read characters that arrive from serial port
  barcode += barcodeDigit;         //each character builds in a string
  cont++;

  if (char(key) == 19) {   //verify the las digit of the scanner

    barcode.remove(cont - 1); //Remove the last digit
    Serial.println(barcode); //Printing the complete code
    cont = 0;
    lectura = true;

  }

};

void color(String estado) {

  switch (estado.toInt()) {
    // rojo - ocupado
    case 0: analogWrite(pinRojo, 255);
      analogWrite(pinAzul, 0);
      analogWrite(pinVerde, 0);
      break;

    // verde - libre
    case 1: analogWrite(pinRojo, 0);
      analogWrite(pinAzul, 0);
      analogWrite(pinVerde, 255);
      break;

    // naranja - reservado
    case 2: analogWrite(pinRojo, 245);
      analogWrite(pinAzul, 33);
      analogWrite(pinVerde, 064);
      break;
  }
}


void compruebaSitio(String barcode) {

  if (client.connect(server, 80)) {
    Serial.println("Connected to server");
    // Make a HTTP request
    client.println("GET /librarinoApp/ardController.php?id=" + id + "&usuario=" + barcode + " HTTP/1.1");
    client.println("Host: librarino.ticsur.es");
    client.println("Connection: close");
    client.println();
  }

  // Leo la respuesta del servidor
  while (client.available()) {
    String line = client.readStringUntil('\r');
    String subline = line.substring(0, 11);
    String subline2 = line.substring(11);
    subline.trim();

    if (subline == "Respuesta:") {
      Serial.println(subline + " ->> " + subline2);
      estadoSitio = subline2;
      color(estadoSitio);
    }
  }

  delay(300);
  client.stop();
}

KbdRptParser Prs;

void setup()
{

  Serial.begin( 9600 );
  Serial.println("Start ");

  // initialize serial for ESP module
  Serial1.begin(9600);
  // initialize ESP module
  WiFi.init(&Serial1);

  // Connect to WPA/WPA2 network
  WiFi.begin(ssid, pass);

  if (Usb.Init() == -1)
    Serial.println("OSC did not start.");

  delay( 200 );

  HidKeyboard.SetReportParser(0, &Prs);

}

void loop()
{

  // Cada 3 segundos compruebo el estado del asiento por si se ha reservado ya
  unsigned long currentMillis = millis();
  if (currentMillis - previousMillis > interval) {
    previousMillis = currentMillis;
    compruebaSitio("");
  }

  Usb.Task();

  if (lectura) {
    compruebaSitio(barcode);
    lectura = false;
    barcode = ""; 
  }
}








