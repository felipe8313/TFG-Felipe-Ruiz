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

//////// Librerías y constantes para la pantalla //////
#include <SerialGraphicLCD.h>
#define maxX 127
#define maxY 63
////////////////////////////////////////////////////////

USB     Usb;
HIDBoot<USB_HID_PROTOCOL_KEYBOARD>    HidKeyboard(&Usb);
String barcode;
char barcodeDigit;
String estadoSitio;
int estadoActual = -1;
int cont = 0;
char pinRojo = A0;
char pinAzul = A2;
char pinVerde = A1;
unsigned long previousMillis = 0;
unsigned long interval = 3000;
bool lectura = false;
char ssid[] = "JAZZTEL_bcUJ";            // your network SSID (name)
char pass[] = "nhpbrpf4nuag";        // your network password
char server[] = "192.168.1.132";
String id = "344";

// Initialize the Ethernet client object
WiFiEspClient client;

LCD LCD;

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
    char barcodeAux[100];
    barcode.toCharArray(barcodeAux, 100);    

    LCD.eraseBlock(0,50,maxX,maxY);//draw box around entire display      
    delay(300);
    LCD.setX((maxX/2)-45);
    LCD.setY((maxY/2 + 20));  
    delay(300);
    LCD.printStr(barcodeAux);
    
    cont = 0;
    lectura = true;

  }

};

void color(String estado) {

  int nuevoEstado = estado.toInt();
  bool hayNuevoEstado = estadoActual != nuevoEstado;
  char* estadoPantalla;

    
  switch (nuevoEstado) {
    // rojo - ocupado
    case 0: analogWrite(pinRojo, 255);
      analogWrite(pinAzul, 0);
      analogWrite(pinVerde, 0);

      if (hayNuevoEstado){
          estadoPantalla = "ASIENTO OCUPADO";
      }     
      break;

    // verde - libre
    case 1: analogWrite(pinRojo, 0);
      analogWrite(pinAzul, 0);
      analogWrite(pinVerde, 255);

      if (hayNuevoEstado){
          estadoPantalla = "ASIENTO LIBRE";
      }       
      break;

    // naranja - reservado
    case 2: analogWrite(pinRojo, 245);
      analogWrite(pinAzul, 33);
      analogWrite(pinVerde, 064);

      if (hayNuevoEstado){
          estadoPantalla = "ASIENTO RESERVADO";
      }         
      break;
      
  }

  if (hayNuevoEstado){
      estadoActual = nuevoEstado;
      LCD.eraseBlock(0,20,maxX,(maxY - 10));//draw box around entire display      
      delay(300);
      LCD.setX((maxX/2)-45);
      LCD.setY((maxY/2)-5);  
      delay(300);
      LCD.printStr(estadoPantalla);
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

  delay(1200);///wait for the one second spalsh screen before anything is sent to the LCD.

  LCD.setHome();//set the cursor back to 0,0.
  LCD.clearScreen();//clear anything that may have been previously printed ot the screen.
  delay(300);

  LCD.setX((maxX/2) - 27);
  LCD.printStr("LIBRARINO");
  LCD.setX((maxX/2)-47);
  LCD.setY((maxY/2)-22);
  LCD.printStr("TELEC. E INFORM.");
  delay(300);
  LCD.drawLine(0,(maxY/2)-13,maxX,(maxY/2)-13,1);//draw line from top left to top right
  delay(300);
  LCD.setX((maxX/2)-45);
  LCD.setY((maxY/2)-5);

  // Inicializo los pines de la bombilla led
  pinMode(pinRojo, OUTPUT);
  pinMode(pinAzul, OUTPUT);
  pinMode(pinVerde, OUTPUT);

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









