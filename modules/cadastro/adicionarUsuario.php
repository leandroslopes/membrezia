<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/MembroDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Membro.php";

$membroDAO = new MembroDAO();
$membro = new Membro();

$membro->setId(filter_input(INPUT_POST, "idMembro"));
$membro->setCargo(filter_input(INPUT_POST, "selectAdicionarUsuario"));
$membro->setSenha(md5("M12345"));

if ($membroDAO->adicionarUsuario($membro)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>  