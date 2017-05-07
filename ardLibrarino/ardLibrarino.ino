/// Librerías para el shield USB y la léctura del código de barras ///
#include <hidboot.h>
#include <usbhub.h>
#include <Wire.h>
#ifdef dobogusinclude
#include <spi4teensy3.h>
#include <SPI.h>
#endif
////////////////////////////////////////////////////////////////////

/////////// Librerías para el módulo WiFi ///////////////
#include "WiFiEsp.h"
#ifndef HAVE_HWSERIAL1
#include "SoftwareSerial.h"
SoftwareSerial Serial1(6, 5); // RX, TX
#endif
////////////////////////////////////////////////////////


//////// Librerías y constantes para la pantalla //////
#include <SerialGraphicLCD.h>
#define maxX 127
#define maxY 63
////////////////////////////////////////////////////////

// Variables para la lectura del código y el control USB
USB     Usb;
HIDBoot<USB_HID_PROTOCOL_KEYBOARD>    HidKeyboard(&Usb);
String barcode;
char barcodeDigit;
int cont = 0;
bool lectura = false;

// Pines para el led RGB
char pinRojo = A0;
char pinAzul = A2;
char pinVerde = A1;

// Variables para el temporizador
unsigned long previousMillis = 0;
unsigned long interval = 8000; // establecemos 8 segundos

// Variables para la conexión a internet
char ssid[] = "iPhone de Felipe";
char pass[] = "felipe8313";
char server[] = "172.20.10.2";

// Número de asiento
String id = "344";

// Variables para indicar el estado actual del asiento
String estadoRecibido;
int estadoActual = -1;

// Clases para el cliente wifi y la pantalla LCD
WiFiEspClient client;
LCD LCD;


// Clase para controlar la lectura del código de barras
class KbdRptParser : public KeyboardReportParser{

  // Métodos para controlar la lectura de un nuevo caráctarer
  protected:
    virtual void OnKeyDown	(uint8_t mod, uint8_t key);
    virtual void OnKeyPressed(uint8_t key);
};

void KbdRptParser::OnKeyDown(uint8_t mod, uint8_t key){
  uint8_t c = OemToAscii(mod, key);

  if (c)
    OnKeyPressed(c);
}


void KbdRptParser::OnKeyPressed(uint8_t key) {

  barcodeDigit = ((char)key); // Leo el caractaer
  barcode += barcodeDigit;     
  cont++;  

  if (char(key) == 19) {   // Último caracter del código
    barcode.remove(cont - 1); // Elimino dicho caracter
    Serial.println(barcode);

    // Transformo el código de String a un array de char para poder imprimirlo en la pantalla LCD
    char barcodeAux[100];
    barcode.toCharArray(barcodeAux, 100);    

    LCD.eraseBlock(0,50,maxX,maxY); // Borro la parte donde se va a imprimir el código     
    delay(300);
    LCD.setX((maxX/2)-45); // Muevo el cursor
    LCD.setY((maxY/2 + 20));  
    delay(300);
    LCD.printStr(barcodeAux); // Muestro el código en la pantalla
    
    cont = 0;
    lectura = true;
  }

};

// Enciende la bombilla del dispositivo de un color u otro y muestra la información del estado en pantalla
void actualizaEstado(String estado) {

  int nuevoEstado = estado.toInt();
  bool hayNuevoEstado = estadoActual != nuevoEstado;
  char* estadoPantalla;

  // Si hay un nuevo estado
  switch (nuevoEstado) {
    // rojo - ocupado
    case 0: 
      analogWrite(pinRojo, 255);
      analogWrite(pinAzul, 0);
      analogWrite(pinVerde, 0);

      if (hayNuevoEstado){
          estadoPantalla = "ASIENTO OCUPADO";
      }     
      break;

    // verde - libre
    case 1: 
      analogWrite(pinRojo, 0);
      analogWrite(pinAzul, 0);
      analogWrite(pinVerde, 255);

      if (hayNuevoEstado){
          estadoPantalla = "ASIENTO LIBRE";
      }       
      break;

    // azul - reservado
    case 2: 
      analogWrite(pinRojo, 0);
      analogWrite(pinAzul, 255);
      analogWrite(pinVerde, 0);

      if (hayNuevoEstado){
          estadoPantalla = "ASIENTO RESERVADO";
      }         
      break;

    // usuario no válido
    case 3: 
      estadoPantalla = "USUARIO NO VALIDO";
      pintaEstado(estadoPantalla); 
      break;
      
  }

  // Muestro el nuevo estado en la pantalla y actualizo el estado actual
  if (hayNuevoEstado){
      estadoActual = nuevoEstado;
      pintaEstado(estadoPantalla);
  }  
}

// Muestra el estado actual en la pantalla LCD
void pintaEstado(char* estado){
    LCD.eraseBlock(0,20,maxX,(maxY)); // Borro la parte del estado anterior     
    delay(300);
    LCD.setX((maxX/2)-45);
    LCD.setY((maxY/2)-5);  
    delay(300);
    LCD.printStr(estado);
}


// Hace una petición GET a la aplicación para obtener el estado actual del asiento
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

    // La respuesta obtenida del servidor es del formato "Respuesta:{estado}"
    if (subline == "Respuesta:") {
      Serial.println(subline + " ->> " + subline2);
      estadoRecibido = subline2;
      actualizaEstado(estadoRecibido);
    }
  }
  
  delay(300);
  client.stop();
}

// Clase para controlador del USB
KbdRptParser Prs;

void setup(){

  Serial.begin( 9600 );
  Serial.println("Start ");

  delay(1200);// Hago una pequeña espera para inicializar la pantalla

  LCD.setHome();// Muevo el cursor al 0,0
  LCD.clearScreen(); // Limpio la pantalla
  delay(300);

  ////////////////// Hago un pequeño encabezado en la pantalla ///////////////////////////
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
  //////////////////////////////////////////////////////////////////////////////////////

  // Inicializo los pines de la bombilla led
  pinMode(pinRojo, OUTPUT);
  pinMode(pinAzul, OUTPUT);
  pinMode(pinVerde, OUTPUT);

  // Inicializo el módulo WiFi
  Serial1.begin(9600);
  WiFi.init(&Serial1);

  // Me conecto a la red
  WiFi.begin(ssid, pass);

  // Inicializo el USB y parseador de caracteres provenientes del lector de código de barras
  Usb.Init();
  delay( 200 );
  HidKeyboard.SetReportParser(0, &Prs);
}

void loop(){

  // Cada 8 segundos compruebo el estado del asiento por si se ha reservado ya
  unsigned long currentMillis = millis();
  if (currentMillis - previousMillis > interval) {
    previousMillis = currentMillis;
    compruebaSitio("");
  }

  // Acción del USB
  Usb.Task();

  // Si ha habido una lectura, actualizo el estado
  if (lectura) {
    compruebaSitio(barcode);
    lectura = false;
    barcode = "";     
  }
 
}









