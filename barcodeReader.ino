#include <hidboot.h>
#include <usbhub.h>

// Satisfy the IDE, which needs to see the include statment in the ino too.
#ifdef dobogusinclude
#include <spi4teensy3.h>
#include <SPI.h>
#endif


USB     Usb;
HIDBoot<USB_HID_PROTOCOL_KEYBOARD>    HidKeyboard(&Usb);
String barcode;
char barcodeDigit;
int estadoSitio = 0; // 0 ocupado, 1 libre
int cont = 0;
int pinRojo = 4;
int pinAzul = 2;
int pinVerde = 3;


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
      
      if (estadoSitio == 1){
        estadoSitio = 0;
        Serial.println("SITIO OCUPADO");
        color('r');
      }else{
        estadoSitio = 1;
        Serial.println("SITIO LIBRE");
        color('v');
      }
      
      
  }
  
};


void color(char color){ //La funcion recibe un parametro que se guarda en variable color
  
  switch(color){ //Se compara variable color con dato guardado
      case 'r': analogWrite(pinRojo,255); 
                analogWrite(pinAzul,0); 
                analogWrite(pinVerde,0);
      break;
      case 'v': analogWrite(pinRojo,0); 
                analogWrite(pinAzul,0); 
                analogWrite(pinVerde,255);
      break;
    }
}


KbdRptParser Prs;

void setup()
{
  Serial.begin( 115200 );
  Serial.println("Start");

  if (Usb.Init() == -1)
    Serial.println("OSC did not start.");

  delay( 200 );

  HidKeyboard.SetReportParser(0, &Prs);
}

void loop()
{
  Usb.Task();  
  
}






