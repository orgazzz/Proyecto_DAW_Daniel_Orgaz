<?php
/**
 * conexion.php
 *
 * Ejemplo CONCEPTUAL de autenticación contra OpenLDAP para DevWebPro.
 * No incluye credenciales reales. En un entorno real se usaría TLS (StartTLS/LDAPS),
 * variables de entorno para configuración y control de errores/logs.
 *
 * Flujo real de autenticación LDAP:
 *  1) Conectar al servidor LDAP.
 *  2) Buscar el DN del usuario mediante un filtro (por ejemplo uid).
 *  3) Realizar bind con el DN encontrado y la contraseña introducida.
 *  4) (Opcional) Consultar grupos para autorizar por rol (developers/designers/managers).
 *
 * @package DevWebPro
 */

declare(strict_types=1);

/**
 * Cliente LDAP minimalista para demostrar el concepto.
 */
final class LdapAuthClient
{
    /** @var string URI del servidor LDAP (ej: ldap://localhost) */
    private string $ldapUri;

    /** @var string Base DN del directorio */
    private string $baseDn;

    /**
     * @param string $ldapUri URI del LDAP (ldap:// o ldaps://)
     * @param string $baseDn  Base DN (dc=devwebpro,dc=local)
     */
    public function __construct(string $ldapUri, string $baseDn)
    {
        $this->ldapUri = $ldapUri;
        $this->baseDn  = $baseDn;
    }

    /**
     * Autentica un usuario realizando un bind con su DN y contraseña.
     *
     * En un entorno real:
     * - Se aplicaría StartTLS/LDAPS
     * - Se sanitizaría entrada (filtros)
     * - Se registrarían intentos y se gestionaría sesión
     *
     * @param string $uid      Identificador del usuario (uid)
     * @param string $password Contraseña introducida por el usuario
     * @return bool True si la autenticación es válida
     */
    public function authenticate(string $uid, string $password): bool
    {
        // 1) Conexión
        $conn = ldap_connect($this->ldapUri);
        if ($conn === false) {
            return false;
        }

        ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($conn, LDAP_OPT_REFERRALS, 0);

        // 2) Buscar DN del usuario (uid=...)
        $safeUid = ldap_escape($uid, '', LDAP_ESCAPE_FILTER);
        $filter  = "(uid={$safeUid})";

        $search = ldap_search($conn, $this->baseDn, $filter, ['dn']);
        if ($search === false) {
            ldap_unbind($conn);
            return false;
        }

        $entries = ldap_get_entries($conn, $search);
        if (!is_array($entries) || ($entries['count'] ?? 0) < 1) {
            ldap_unbind($conn);
            return false;
        }

        $userDn = $entries[0]['dn'];

        // 3) Bind con las credenciales del usuario
        $ok = @ldap_bind($conn, $userDn, $password);
        ldap_unbind($conn);

        return (bool)$ok;
    }
}
