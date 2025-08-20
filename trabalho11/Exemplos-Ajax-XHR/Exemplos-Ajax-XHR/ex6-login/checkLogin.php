<?php

class LoginResult
{
  public $isAuthorized;
  public $newLocation;

  function __construct($isAuthorized, $newLocation)
  {
    $this->isAuthorized = $isAuthorized;
    $this->newLocation = $newLocation;
  }
}


// classe para definir o json


$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

// Validação simplificada para fins didáticos. Não faça isso!
if ($email == 'fulano@mail.com' && $senha == '123456')
  $loginResult = new LoginResult(true, 'home.html');
// se a senha for 123456, redireciona para a página de sucesso
else
  $loginResult = new LoginResult(false, '');

// se a senha for diferente de 123456, não acontece nada, o novo local é nulo, então continua na página de login


header('Content-type: application/json');
echo json_encode($loginResult);