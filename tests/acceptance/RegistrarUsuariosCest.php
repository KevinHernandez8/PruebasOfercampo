<?php 

/**
 * HU004-1 Registrar usuarios
 */
class RegistrarUsuariosCest
{
    // IMPORTANTE! Cambiar correo de prueba cada vez que se ejecute
    private $correoPrueba = "prueba.versionuno";
    private $nombre = "Alejandro Probando";
    private $contrasena = "prueba123";

    public function _before(AcceptanceTester $I)
    {
        $I->amOnUrl('http://test.ticmakers.com/ofercampo/web/');
        $I->wait(5);
    }

    // tests
    /**
     * OFA-1:HU004-1-1 Registro exitoso
     */
    public function registroExitosoTest(AcceptanceTester $I)
    {
        $I->wantToTest('Registro de un usuario ingresando datos correctos, siendo el resultado exitoso');
        $I->click('Registrarme');
        $I->wait(5);

        $I->amGoingTo("Ingresar el nombre '$this->nombre'");
        $I->fillField("#register-name", $this->nombre);
        
        $I->amGoingTo('Seleccionar la ciudad "Ibagué"');
        $lista = '/html[1]/body[1]/div[2]/div[1]/div[1]/div[2]/div[1]/div[1]/div[1]/div[1]/div[1]/form[1]/div[1]/div[4]/div[1]/span[2]/span[1]/span[1]';
        $I->click($lista);
        $I->wait(1);
        $I->click('/html[1]/body[1]/span[1]/span[1]/span[2]/ul[1]/li[433]');
        $I->wait(1);
        $I->amGoingTo('Presionar Tab');
        $I->pressKey($lista, WebDriverKeys::TAB);
        $I->expectTo('Pasa al campo "E-mail"');
        $focus = $I->executeJS('return jQuery("#register-email").is(":focus")');
        $I->assertTrue($focus, "El componente E-mail es seleccionado al dar Tab.");

        $I->amGoingTo("Ingresar el e-mail $this->correoPrueba@maildrop.cc");
        $I->fillField('#register-email', $this->correoPrueba.'@maildrop.cc');
        $I->amGoingTo('Presionar Tab');
        $I->pressKey('#register-email', WebDriverKeys::TAB);
        $I->expectTo('Pasa al campo "Contraseña"');
        $focus = $I->executeJS('return jQuery("#register-password").is(":focus")');
        $I->assertTrue($focus, "El componente Contraseña es seleccionado al dar Tab.");

        $I->amGoingTo("Ingresar la contraseña '$this->contrasena'");
        $I->fillField('#register-password', $this->contrasena);
        $I->amGoingTo('Presionar Tab');
        $I->pressKey('#register-password', WebDriverKeys::TAB);
        $I->expectTo('Pasa al campo "Confirmar contraseña"');
        $focus = $I->executeJS('return jQuery("#register-confirmpassword").is(":focus")');
        $I->assertTrue($focus, "El componente Confirmar contraseña no es seleccionado al dar Tab."); 

        $I->amGoingTo("Ingresar la contraseña '$this->contrasena'");
        $I->fillField('#register-confirmpassword', $this->contrasena);

        $I->click('register-button');
        $I->wait(15);
        $I->canSee("Se envio un correo electrónico de confirmación");
    }

    /**
     * OFA-2:HU004-1-2 Confirmar cuenta
     */
    public function confirmarCuentaTest(AcceptanceTester $I)
    {
        $I->wantToTest('Confirmar creación de la cuenta desde el correo eléctronico');
        $I->amGoingTo('Esperar 2 minutos a que llegue el correo');
        $I->wait(120);
        $I->amOnUrl('https://maildrop.cc/inbox/'.$this->correoPrueba);
        $I->wait(2);
        $I->canSee("$this->correoPrueba@maildrop.cc", "//h1[@class='inbox-title']");
        $I->amGoingTo('Clic el enlace para confirmar cuenta');
        $I->click("//a[@class='messagelist-row-link']");
        $I->wait(4);
        $I->switchToIFrame("//iframe[@class='messagedata-iframe']");
        $I->click("//a[contains(text(),'Confirmar mi cuenta')]");
        $I->wait(4);
        $I->expectTo('Una nueva pestaña con la modal de inicio de sesión');
        $I->switchToNextTab();
        //$I->canSee("Su cuenta ya ha sido confirmada. Ahora puedes ir a iniciar sesión");
        $I->expectTo('Mensaje de notificación');
        $I->canSee("Su cuenta ha sido confirmada");
        $I->canSeeInTitle('Ofercampo');
        $I->canSeeElement("#login-username");

    }

    /**
     * OFA-3:HU004-1-3 Registro no exitoso
     */
    public function registroNoExitosoUnoTest(AcceptanceTester $I)
    {
        $I->wantToTest('Validar los campos del formulario de registro');
        $I->click('Registrarme');
        $I->wait(5);
        $I->amGoingTo('Dejar campos en blanco y dar clic en Registrar');
        $I->expectTo('Una alerta en cada campo indicando que no puede estar vacío');
        $I->click('register-button');
        $I->wait(1);
        $I->canSee('Nombre no puede estar vacío.');
        $I->canSee('Ciudad o municipio no puede estar vacío.');
        $I->canSee('Email no puede estar vacío.');
        $I->canSee('Contraseña no puede estar vacío.');
        $I->canSee('Confirmar contraseña no puede estar vacío.');
    }

    public function registroNoExitosoDosTest(AcceptanceTester $I)
    {
        $I->wantToTest('Validar los campos del formulario de registro');
        $I->click('Registrarme');
        $I->wait(5);
        $I->amGoingTo('Validar la longitud del campo Nombre');
        $I->expectTo('Un mensaje de alerta indicando que el nombre es muy largo');
        $I->fillField("#register-name", "Alejandro Hernández Detin Marin Dedopin Gue Pruebas Uno Dos Tres Cuatro Cinco Seis Siete");
        // $I->expectTo('No ver el elemento inactivo en la lista');
        // $lista = '/html[1]/body[1]/div[2]/div[1]/div[1]/div[2]/div[1]/div[1]/div[1]/div[1]/div[1]/form[1]/div[1]/div[4]/div[1]/span[2]/span[1]/span[1]';
        // $I->click($lista);
        // $I->cantSee("Inactivo");
        $I->expectTo('Un mensaje de alerta indicando que el correo no acepta caracteres especiales');
        $I->fillField('#register-email', 'correo#%especial');
        $I->click('register-button');
        $I->wait(1);
        $I->canSee('Nombre es invalido');
        $I->canSee('Email es inválido');
    }

    public function registroNoExitosoTresTest(AcceptanceTester $I)
    {
        $I->wantToTest('Validar que el Email ya se encuentra registrado');
        $I->click('Registrarme');
        $I->wait(5);

        $I->amGoingTo('Ingresar el nombre "Alejandro Repetido"');
        $I->fillField("#register-name", "Alejandro Repetido");
        
        $I->amGoingTo('Seleccionar la ciudad "Ibagué"');
        $lista = '/html[1]/body[1]/div[2]/div[1]/div[1]/div[2]/div[1]/div[1]/div[1]/div[1]/div[1]/form[1]/div[1]/div[4]/div[1]/span[2]/span[1]/span[1]';
        $I->click($lista);
        $I->wait(1);
        $I->click('/html[1]/body[1]/span[1]/span[1]/span[2]/ul[1]/li[433]');
        $I->wait(1);

        $I->amGoingTo("Ingresar el e-mail $this->correoPrueba@maildrop.cc");
        $I->fillField('#register-email', $this->correoPrueba.'@maildrop.cc');

        $I->amGoingTo("Ingresar la contraseña '$this->contrasena'");
        $I->fillField('#register-password', $this->contrasena);

        $I->amGoingTo("Ingresar la contraseña '$this->contrasena'");
        $I->fillField('#register-confirmpassword', $this->contrasena);

        $I->click('register-button');
        $I->wait(1);
        $I->canSee("No es posible crear el usuario debido a que el Email, ya se encuentra registrado");
    }

    public function registroNoExitosoCuatroTest(AcceptanceTester $I)
    {
        $I->wantToTest('Validar que las contraseñas no coinciden');
        $I->click('Registrarme');
        $I->wait(5);

        $I->amGoingTo('Ingresar el nombre "Alejandro Contraseña"');
        $I->fillField("#register-name", "Alejandro Contraseña");
        
        $I->amGoingTo('Seleccionar la ciudad "Ibagué"');
        $lista = '/html[1]/body[1]/div[2]/div[1]/div[1]/div[2]/div[1]/div[1]/div[1]/div[1]/div[1]/form[1]/div[1]/div[4]/div[1]/span[2]/span[1]/span[1]';
        $I->click($lista);
        $I->wait(1);
        $I->click('/html[1]/body[1]/span[1]/span[1]/span[2]/ul[1]/li[433]');
        $I->wait(1);

        $I->amGoingTo("Ingresar el e-mail $this->correoPrueba@maildrop.cc");
        $I->fillField('#register-email', $this->correoPrueba.'@maildrop.cc');

        $I->amGoingTo('Ingresar la contraseña "prueba123"');
        $I->fillField('#register-password', 'prueba123');

        $I->amGoingTo('Ingresar la contraseña "pruebadiferente"');
        $I->fillField('#register-confirmpassword', 'pruebadiferente');

        $I->click('register-button');
        $I->wait(1);
        $I->canSee("Las contraseñas no coinciden. Por favor verifique");
    }
}
