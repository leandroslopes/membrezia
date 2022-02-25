<?
session_start();

include_once $_SERVER["DOCUMENT_ROOT"] . "/membrezia/source/dao/MembroDAO.php";

$membroDAO = new MembroDAO();

if ($membroDAO->login($_POST)) { 
    $_SESSION["usuario"] = $membroDAO->getUsuario($_POST);
    header("Location: inicio.php");
} else {
    header("Location: index.php?q=1");
}
?>
