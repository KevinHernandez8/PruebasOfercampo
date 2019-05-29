<?php 

/**
 * HU004-3-2 Perfil - Tab datos de acceso
 */
class PerfilDatosAccesoCest
{
    private $correo = 'sebastianmr320@gmail.com';
    private $contrasena = '87654321';
    private $contrasenaNueva = '12345678';

    // COOKIES --------------------
    private $phpsessid = "";
    private $user_email = "";
    private $csrf = "";

    public function _before(AcceptanceTester $I)
    {
        $I->amOnUrl('http://test.ticmakers.com/ofercampo/web/');
        $I->wait(3);
    }

    /**
     * Inicia sesión antes de pasar a ejecutar los casos de prueba
     */
    public function antesDeTodo(AcceptanceTester $I)
    {
        $I->click('Iniciar sesión');
        $I->wait(3);
        $I->fillField('#login-username', $this->correo);
        $I->fillField('#login-password', $this->contrasena);
        $I->click('Inicia sesión');
        $I->wait(10);
        $this->phpsessid = $I->grabCookie('PHPSESSID');
        $this->user_email = $I->grabCookie('__user_email');
        $this->csrf = $I->grabCookie('_csrf');
    }

    /**
     * OFA-9:HU004-3-2-1 Edición exitosa (1)
     */
    public function edicionExitosaUnoTest(AcceptanceTester $I)
    {
        $I->setCookie('PHPSESSID', $this->phpsessid);
        $I->setCookie('__user_email', $this->user_email);
        $I->setCookie('_csrf', $this->csrf);
        $I->reloadPage();
        $I->wait(1);

        $I->wantToTest("Actualización de datos sin ingresar nada");
        $I->click('Mi cuenta');
        $I->wait(2);
        $I->click('Mis datos');
        $I->wait(2);
        $I->click('Asignación usuario');
        $I->amGoingTo('Clic el botón "Guardar" con los campos en blanco');
        $I->expectTo('Guarda la información tal como está sin pedir campos como obligatorios');
        $I->click('#send');
        $I->wait(2);
        $I->canSee('Se han registrado correctamente los datos de su perfil');
    }

    /**
     * OFA-9:HU004-3-2-1 Edición exitosa (2)
     */
    public function edicionExitosaDosTest(AcceptanceTester $I)
    {
        $I->setCookie('PHPSESSID', $this->phpsessid);
        $I->setCookie('__user_email', $this->user_email);
        $I->setCookie('_csrf', $this->csrf);
        $I->reloadPage();
        $I->wait(1);

        $I->wantToTest("Actualización de datos ingresando todos los datos correctos");
        $I->click('Mi cuenta');
        $I->wait(2);
        $I->click('Mis datos');
        $I->wait(2);
        $I->click('Asignación usuario');
        $I->amGoingTo('Llenar el formulario con los datos correctos');
        $I->expectTo('Los datos se actualizarán de manera correcta');
        $I->fillField('#register-old_password', $this->contrasena);
        $I->fillField('#register-password', $this->contrasenaNueva);
        $I->fillField('#register-confirmpassword', $this->contrasenaNueva);
        $I->click('#send');
        $I->wait(2);
        $I->canSee('Se han registrado correctamente los datos de su perfil');
    }

    /**
     * OFA-9:HU004-3-2-2 Edición no exitosa (1)
     */
    public function edicionNoExitosaUnoTest(AcceptanceTester $I)
    {
        $I->setCookie('PHPSESSID', $this->phpsessid);
        $I->setCookie('__user_email', $this->user_email);
        $I->setCookie('_csrf', $this->csrf);
        $I->reloadPage();
        $I->wait(1);

        $I->wantToTest("Validación de la contraseña antigua con un valor diferente al que se encuetra registrado.");
        $I->click('Mi cuenta');
        $I->wait(2);
        $I->click('Mis datos');
        $I->wait(2);
        $I->click('Asignación usuario');
        $I->amGoingTo('Ingresar "contraseñadiferente" en el campo "Contraseña antigua"');
        $I->fillField('#register-old_password', 'contraseñadiferente');
        $I->amGoingTo("Ingresar el par de contraseñas válidas '$this->contrasenaNueva'");
        $I->fillField('#register-password', $this->contrasenaNueva);
        $I->fillField('#register-confirmpassword', $this->contrasenaNueva);
        $I->expectTo('Una alerta indicando que la contraseña antigua no coincide');
        $I->click('#send');
        $I->wait(2);
        $I->canSee('El valor ingresado como contraseña actual es inválido');
    }

    /**
     * OFA-9:HU004-3-2-2 Edición no exitosa (2)
     */
    public function edicionNoExitosaDosTest(AcceptanceTester $I)
    {
        $I->setCookie('PHPSESSID', $this->phpsessid);
        $I->setCookie('__user_email', $this->user_email);
        $I->setCookie('_csrf', $this->csrf);
        $I->reloadPage();
        $I->wait(1);

        $I->wantToTest("Validar que la contraseña nueva y su confirmación coinciden.");
        $I->click('Mi cuenta');
        $I->wait(2);
        $I->click('Mis datos');
        $I->wait(2);
        $I->click('Asignación usuario');
        $I->amGoingTo("Ingresar '$this->contrasena' en el campo 'Contraseña antigua'");
        $I->fillField('#register-old_password', $this->contrasena);
        $I->amGoingTo("Ingresar en 'Contraseña nueva' = '$this->contrasenaNueva' y en 'Confirmar contrasña' = '987654321'");
        $I->fillField('#register-password', $this->contrasenaNueva);
        $I->fillField('#register-confirmpassword', '987654321');
        $I->expectTo('Una alerta indicando que las contraseñas no coinciden');
        $I->click('#send');
        $I->wait(2);
        $I->canSee('Las contraseñas no coinciden');
    }

    /**
     * OFA-9:HU004-3-2-2 Edición no exitosa (3)
     */
    public function edicionNoExitosaTresTest(AcceptanceTester $I)
    {
        $I->setCookie('PHPSESSID', $this->phpsessid);
        $I->setCookie('__user_email', $this->user_email);
        $I->setCookie('_csrf', $this->csrf);
        $I->reloadPage();
        $I->wait(1);

        $I->wantToTest("Validar la longitud de las contraseñas.");
        $I->click('Mi cuenta');
        $I->wait(2);
        $I->click('Mis datos');
        $I->wait(2);
        $I->click('Asignación usuario');
        $I->amGoingTo("Ingresar '$this->contrasena' en el campo 'Contraseña antigua'");
        $I->fillField('#register-old_password', $this->contrasena);
        $I->amGoingTo("Ingresar en ambos campos la contraseña 'abc123'");
        $I->fillField('#register-password', 'abc123');
        $I->fillField('#register-confirmpassword', 'abc123');
        $I->expectTo('Una alerta indicando que la contraseña es muy corta');
        $I->click('#send');
        $I->wait(2);
        $I->canSee('Contraseña debería contener al menos 8 letras');
    }

    /**
     * OFA-9:HU004-3-2-2 Edición no exitosa (4)
     */
    public function edicionNoExitosaCuatroTest(AcceptanceTester $I)
    {
        $I->setCookie('PHPSESSID', $this->phpsessid);
        $I->setCookie('__user_email', $this->user_email);
        $I->setCookie('_csrf', $this->csrf);
        $I->reloadPage();
        $I->wait(1);

        $I->wantToTest("Validar los campos requeridos.");
        $I->click('Mi cuenta');
        $I->wait(2);
        $I->click('Mis datos');
        $I->wait(2);
        $I->click('Asignación usuario');
        $I->amGoingTo("Dar clic en el campo 'Contraseña antigua', dejarlo en blanco y dar clic en guardar");
        $I->expectTo('Los campos se vuelven obligatorios. Una alerta indicando que los campos no pueden estar vacíos');
        $I->click('#register-old_password');
        $I->click('#send');
        $I->wait(2);
        $I->canSee('Contraseña antigua no puede estar vacío');
        $I->canSee('Contraseña nueva no puede estar vacío');
        $I->canSee('Confirmar contraseña no puede estar vacío');
    }
}
