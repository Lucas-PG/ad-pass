<?php
class ActiveDirectoryService
{
  private $ldap;

  public function __construct()
  {
    // if (!$this->connect()) {
    //   throw new Exception('Não foi possível se conectar ao Active Directory');
    // }
  }

  /**
   * Tenta se conectar ao ActiveDirectory
   *
   * @return boolean Retorna true se a conexão foi bem sucedida e false caso contrário
   **/
  private function connect(string $admin, string $adminPassword): bool
  {
    $this->ldap = ldap_connect("ldaps://" . $_ENV['AD_HOST'] . ":636");
    ldap_set_option($this->ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($this->ldap, LDAP_OPT_REFERRALS, 0);

    $ad_bind_dn = explode("@", $admin)[0] . '@' . $_ENV['AD_HOST'];

    $ldapbind = ldap_bind(
      $this->ldap,
      $ad_bind_dn,
      $adminPassword
    );

    if (!$ldapbind) {
      return false;
    }

    return true;
  }

  /**
   * Verifica se um usuário existe no ActiveDirectory
   *
   * @param string $login Nome do usuário
   * @return boolean Retorna true se o usuário existe e false caso contrário
   **/
  public function userExists(string $login): bool
  {
    $login = explode('@', $login)[0]; // Aceita login e email
    $filter = "(&(!(userAccountControl:1.2.840.113556.1.4.803:=2))(uid=$login))"; // Verifica se usuário está ativo
    $result = ldap_search($this->ldap, $_ENV['AD_USER_DN'], $filter);
    if (!$result) {
      return false;
    }
    $entries = ldap_get_entries($this->ldap, $result);
    return $entries['count'] > 0;
  }


  /**
   * Reseta a senha de um usuário no ActiveDirectory
   *
   * @param string $login Nome do usuário
   * @param string $newPassword Nova senha
   * @return boolean Retorna true se a senha foi alterada com sucesso e false caso contrário
   **/
  public function resetPassword(string $admin, string $adminPassword, string $login, string $newPassword): bool
  {
    if (!$this->connect($admin, $adminPassword)) {
      $_SESSION['message'] = "Error connecting to Active Directory";
      header("Location: /");
      exit;
    }

    if (!$this->userExists($login)) {
      return false;
    }

    $modify_dn = $this->getUserDN($login);

    if (!$modify_dn) {
      return false;
    }

    // https://learn.microsoft.com/pt-br/troubleshoot/windows-server/active-directory/change-windows-active-directory-user-password
    $modifications = [
      [
        "attrib" => "unicodePwd",
        "modtype" => LDAP_MODIFY_BATCH_REPLACE,
        "values" => [$this->adifyPw($newPassword)]
      ]
    ];

    // Aceita trocar a senha sem certificado
    // NÃO DEVE SER USADO EM PRODUÇÃO, APENAS PARA FINS DE TESTE
    @ldap_set_option($this->ldap, LDAP_OPT_X_TLS_REQUIRE_CERT, LDAP_OPT_X_TLS_NEVER);

    $result = ldap_modify_batch($this->ldap, $modify_dn, $modifications);
    return $result;
  }

  /**
   * Retorna o DN de um usuário
   *
   * @param string $login Nome do usuário
   * @return string|false Retorna o DN do usuário ou false caso o usuário não exista
   **/
  public function getUserDN(string $login): string|false
  {
    $filter = "(uid=$login)";
    $result = ldap_search($this->ldap, $_ENV['AD_USER_DN'], $filter, array('dn'));
    if (!$result) {
      return false;
    }
    $entries = ldap_get_entries($this->ldap, $result);
    if ($entries['count'] === 0) {
      return false;
    }

    return $entries[0]['dn'];
  }

  /**
   * Adiciona aspas e converte a senha para UTF-16LE
   *
   * @param string $password Senha a ser convertida
   * @return string Senha convertida
   **/
  public function adifyPw($password): string
  {
    // https://learn.microsoft.com/en-us/openspecs/windows_protocols/ms-adts/6e803168-f140-4d23-b2d3-c3a8ab5917d2?redirectedfrom=MSDN
    return iconv("UTF-8", "UTF-16LE", '"' . $password . '"');
  }
}
