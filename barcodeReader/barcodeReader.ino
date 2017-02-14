#include <hidboot.h>
#include <usbhub.h>
#include <Wire.h>
#include <Ciao.h>


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
void KbdRptParser::OnKeyPressed(uint8_t key){
 
  barcodeDigit=((char)key);  // Read characters that arrive from serial port
  barcode += barcodeDigit;         //each character builds in a string
  cont++;
 
  if (char(key)== 19) {    //verify the las digit of the scanner
  
      barcode.remove(cont-1);  //Remove the last digit
      Serial.println(barcode); //Printing the complete code
      barcode="";                       // Reset the counter
      cont = 0;
      lectura = true;
                  
  }
  
};

void color(String estado){
  
  switch(estado.toInt()){
      // rojo - ocupado
      case 0: analogWrite(pinRojo,255); 
                analogWrite(pinAzul,0); 
                analogWrite(pinVerde,0);
      break;
      
      // verde - libre
      case 1: analogWrite(pinRojo,0); 
                analogWrite(pinAzul,0); 
                analogWrite(pinVerde,255);
      break;
      
      // naranja - reservado
      case 2: analogWrite(pinRojo,245); 
                analogWrite(pinAzul,33); 
                analogWrite(pinVerde,064);
      break;
    }
}


void compruebaSitio(String url){

    CiaoData data = Ciao.write("rest", "librarino.ticsur.es", url);
    if (!data.isEmpty()){
      estadoSitio = data.get(2);
      color(estadoSitio);
      Serial.println( "Response: " + estadoSitio);
    }else{ 
      Serial.println ("Write Error");
    } 

}

KbdRptParser Prs;

void setup()
{
  
  Serial.begin( 115200 );
  Serial.println("Start ");
  Ciao.begin();

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
    compruebaSitio("/librarinoApp/ardCompAsiento.php");    
  }
  
  Usb.Task();
    
  if (lectura){
    compruebaSitio("/librarinoApp/ardController.php");
    lectura = false;
  }  
}









