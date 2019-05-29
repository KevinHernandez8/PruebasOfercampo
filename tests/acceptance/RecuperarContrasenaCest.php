<?php 

/**
 * HU004-4 Recuperar contraseña
 */
class RecuperarContrasenaCest
{
    private $correoPrueba = 'qaqaqa';
    private $olvidoContrasena = "/html[1]/body[1]/div[2]/div[1]/div[1]/div[2]/div[1]/div[1]/div[1]/div[1]/div[2]/form[1]/div[3]/div[1]/a[1]";

    public function _before(AcceptanceTester $I)
    {
        $I->amOnUrl('http://test.ticmakers.com/ofercampo/web/');
        $I->wait(5);
        $I->amGoingTo('Ingresar al formulario para recuperar contraseña');
        $I->click('Iniciar sesión');
        $I->wait(3);
        $I->click($this->olvidoContrasena);
        $I->wait(5);
    }

    // tests
    /**
     * OFA-12:HU004-4-1 Validación de correo
     */
    public function validacionCorreoUnoTest(AcceptanceTester $I)
    {
        $I->wantToTest('Valida que la dirección de correo ingresada no está registrada');
        $I->amGoingTo('Ingresar un correo que no está registrado');
        $I->fillField('#dynamicmodel-email', 'invalido@gmail.com');
        $I->click('Restaurar contraseña');
        $I->wait(2);
        $I->expectTo('Una alerta que indique que el correo ingresado no se encuentra registrado');
        $I->canSee('This email is not registered, please try again');
    }

    public function validacionCorreoDosTest(AcceptanceTester $I)
    {
        $I->wantToTest('Verifica que la dirección de correo electrónico no contenga caracteres especiales');
        $I->amGoingTo('Ingresar un correo inválido');
        $I->fillField('#dynamicmodel-email', 'soy$%incorrecto@maildrop.cc');
        $I->click('Restaurar contraseña');
        $I->wait(2);
        $I->expectTo('Una alerta que indique que el correo ingresado no es válido');
        $I->canSee('Correo electrónico es inválido');
    }

    /**
     * OFA-13:HU004-4-2 Correo válido, envío de notificación
     */
    public function notificacionCorreoTest(AcceptanceTester $I)
    {
        $I->wantToTest('Verifica que al ingresar un correo válido se genere la notificación');
        $I->amGoingTo("Ingresar el correo $this->correoPrueba@maildrop.cc");
        $I->fillField('#dynamicmodel-email', "$this->correoPrueba@maildrop.cc");
        $I->click('Restaurar contraseña');
        $I->wait(3);
        $I->expectTo('Notificación indicando que el mensaje fue enviado');
        $I->canSee('Solicitud de cambio de contraseña');
    }

    /**
     * OFA-14:HU004-4-3 Restablecimiento contraseña exitoso
     */
    public function restableceExitosoTest(AcceptanceTester $I)
    {
        $I->wantToTest('Ingresar desde el correo a restablecer contraseña y lo hace de forma exitosa');
        $I->amGoingTo('Esperar 2 minutos a que llegue el correo');
        // $I->wait(120);
        $I->amOnUrl("https://maildrop.cc/inbox/$this->correoPrueba");
        $I->wait(2);
        $I->canSee("$this->correoPrueba@maildrop.cc", "//h1[@class='inbox-title']");
        $I->amGoingTo('Clic el enlace para restablecer contraseña');
        $I->click("//a[@class='messagelist-row-link']");
        $I->wait(4);
        $I->switchToIFrame("//iframe[@class='messagedata-iframe']");
        $I->click("//a[contains(text(),'Restablecer contraseña')]");
        $I->wait(4);
        $I->expectTo('Una nueva pestaña con la modal de restablecer contraseña');
        $I->switchToNextTab();
        $I->canSee('Restablecer contraseña');
        $I->amGoingTo('Ingresar el par de contraseñas "nueva123"');
        $I->fillField('#users-password', 'nueva123');
        $I->fillField('#users-confirmpassword', 'nueva123');
        $I->click("//button[@name='register-button']");
        $I->wait(2);
        $I->expectTo('Mensaje notificando que el cambio de contraseña fue realizado con éxito');
        $I->canSee('La contraseña ha sido actualizada');
    }

    /**
     * OFA-15:HU004-4-4 Restablecimiento de contraseña no exitoso
     */
    public function restableceNoExitosoUnoTest(AcceptanceTester $I)
    {
        $I->wantToTest('Validar los campos del formulario de restablecimiento de contraseña');
        $I->amOnUrl("https://maildrop.cc/inbox/$this->correoPrueba");
        $I->wait(2);
        $I->canSee("$this->correoPrueba@maildrop.cc", "//h1[@class='inbox-title']");
        $I->amGoingTo('Clic el enlace para restablecer contraseña');
        $I->click("//a[@class='messagelist-row-link']");
        $I->wait(4);
        $I->switchToIFrame("//iframe[@class='messagedata-iframe']");
        $I->click("//a[contains(text(),'Restablecer contraseña')]");
        $I->wait(4);
        $I->expectTo('Una nueva pestaña con la modal de restablecer contraseña');
        $I->switchToNextTab();
        $I->canSee('Restablecer contraseña');
        $I->amGoingTo('Ingresar el par de contraseñas "nueva123" y "incorrecto123"');
        $I->fillField('#users-password', 'nueva123');
        $I->fillField('#users-confirmpassword', 'incorrecto123');
        $I->click("//button[@name='register-button']");
        $I->wait(2);
        $I->expectTo('Mensaje notificando que las contraseñas no coinciden');
        $I->canSee('Las contraseñas no coinciden. Por favor verifique');
    }

    public function restableceNoExitosoDosTest(AcceptanceTester $I)
    {
        $I->wantToTest('Validar los campos del formulario de restablecimiento de contraseña');
        $I->amOnUrl("https://maildrop.cc/inbox/$this->correoPrueba");
        $I->wait(2);
        $I->canSee("$this->correoPrueba@maildrop.cc", "//h1[@class='inbox-title']");
        $I->amGoingTo('Clic el enlace para restablecer contraseña');
        $I->click("//a[@class='messagelist-row-link']");
        $I->wait(4);
        $I->switchToIFrame("//iframe[@class='messagedata-iframe']");
        $I->click("//a[contains(text(),'Restablecer contraseña')]");
        $I->wait(4);
        $I->expectTo('Una nueva pestaña con la modal de restablecer contraseña');
        $I->switchToNextTab();
        $I->canSee('Restablecer contraseña');
        $I->amGoingTo('Dejar los campos en blanco y dar clic en Registrar');
        $I->click("//button[@name='register-button']");
        $I->wait(2);
        $I->expectTo('Mensaje notificando que los campos no pueden estar vacios');
        $I->canSee('Password no puede estar vacío');
        $I->canSee('Confirm Password no puede estar vacío');
    }
}