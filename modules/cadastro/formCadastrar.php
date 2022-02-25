<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/SystemUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/DataUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/SexoUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/CargoUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/EstadoCivilUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/util/CartaUtil.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/ModuloDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/MembroDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/CongregacaoDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/FuncaoDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/dao/EstadoDAO.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Membro.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Congregacao.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Funcao.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Estado.php";
include_once "{$_SERVER["DOCUMENT_ROOT"]}/membrezia/source/modelo/Cidade.php";

$moduloDAO = new ModuloDAO();
$membroDAO = new MembroDAO();
$congregacaoDAO = new CongregacaoDAO();
$funcaoDAO = new FuncaoDAO();
$estadoDAO = new EstadoDAO();

$membro = new Membro();

$idModulo = filter_input(INPUT_GET, "id");
$idCadastrado = filter_input(INPUT_GET, "idCadastrado");

$congregacao = new Congregacao();
$funcao = new Funcao();
$estado = new Estado();
$cidade = new Cidade();

$cidade->setEstado($estado);

$membro->setCongregacao($congregacao);
$membro->setFuncao($funcao);
$membro->setCidade($cidade);

if (isset($idCadastrado)) {
    $usuarioCadastroDAO = new MembroDAO();
    $membroDAO = new MembroDAO();
    $usuarioCadastro = new Membro();
    
    $membro = $membroDAO->getMembro($idCadastrado);
    $usuarioCadastro = $usuarioCadastroDAO->getMembro($membro->getUsuarioCadastro()->getId());
}

$nomeModulo = $moduloDAO->getModulo($idModulo)->getNome();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?= SystemUtil::TITLE_SYSTEM . ' | ' . $nomeModulo ?> | CADASTRAR</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link type="image/x-icon" rel="icon" href="../../public/images/favicon.png" />
        <link type="text/css" rel="stylesheet" href="../../public/css/style.css"/>

        <link type="text/css" rel="stylesheet" href="../../public/js/jquery-ui-1.10.1/css/custom-theme/jquery-ui-1.10.1.custom.css"/>  
        <script type="text/javascript" src="../../public/js/jquery-ui-1.10.1/js/jquery-1.9.1.js"></script>
        <script type="text/javascript" src="../../public/js/jquery-ui-1.10.1/js/jquery-ui-1.10.1.custom.js"></script>

        <script type="text/javascript" src="../../public/js/masked-input-1.3.1/jquery.maskedinput.js"></script>
        <script type="text/javascript" src="../../public/js/jquery-maskmoney/jquery.maskmoney.js"></script>
        <script type="text/javascript" src="../../public/js/scripts/jquery-masks.js"></script>

        <script type="text/javascript" src="../../public/js/jquery-validate-1.11.0/jquery.validate.js"></script>
        <script type="text/javascript" src="../../public/js/scripts/jquery-validate-form.js"></script>

        <script type="text/javascript" src="../../public/js/scripts/jquery-dialog-msg.js"></script>
        <script type="text/javascript" src="../../public/js/scripts/jquery-funcoes.js"></script>
        <script type="text/javascript" src="../../public/js/scripts/jquery-dialog-form-cadastro.js"></script>

        <script type="text/javascript">
            var getUrl = function() {
                var url, idModulo;

                idModulo = <?= $idModulo ?>;
                url = "index.php?&id=" + idModulo;

                return url;
            };

            var selectCidades = function(idEstado, idCidade = "") {
                $.ajax({
                    type: "post",
                    url: "../../public/js/scripts/ajax.php",
                    dataType: "json",
                    data: {
                        tipo: "cidades", 
                        idEstado: idEstado,
                        idCidade: idCidade
                    },
                    success: function(option) {
                        var options, selected; 
                        
                        options = '<option value="">SELECIONE UMA CIDADE</option>';
                        selected = "";
                        
                        for (var i = 0; i < option.length; i++) {
                            if (option[i].id == idCidade) {
                                selected = "selected";
                            }    
                            options += '<option ' + selected + ' value="' + option[i].id + '">' + option[i].nome + '</option>';
                            selected = "";
                        }	
                        $('#idCidade').html(options);
                    }
                });
            };
        </script>
    </head>
    <body>

        <? include "../../pageComponents/message.php"; ?>

        <?
        $btnCadastrar = filter_input(INPUT_POST, "btnCadastrar");

        if (!empty($btnCadastrar)) {
            $membroDAO = new MembroDAO();
            $membro = new Membro();
            $congregacao = new Congregacao();
            $funcao = new Funcao();
            $cidade = new Cidade();

            $idMembro = filter_input(INPUT_POST, "idMembro");
            $url = "index.php?id=$idModulo";

            $membro->setNome(filter_input(INPUT_POST, "nome"));
            $membro->setRua(filter_input(INPUT_POST, "rua"));
            $membro->setBairro(filter_input(INPUT_POST, "bairro"));
            $membro->setComplemento(filter_input(INPUT_POST, "complemento"));
            
            $cidade->setId(filter_input(INPUT_POST, "idCidade"));
            $membro->setCidade($cidade);
            
            $membro->setFone(filter_input(INPUT_POST, "fone"));
            $membro->setDataNascimento(filter_input(INPUT_POST, "dataNascimento"));
            $membro->setSexo(filter_input(INPUT_POST, "sexo"));
            $membro->setEstadoCivil(filter_input(INPUT_POST, "estadoCivil"));
            $membro->setNaturalidade(filter_input(INPUT_POST, "naturalidade"));
            $membro->setRg(filter_input(INPUT_POST, "rg"));
            $membro->setCpf(filter_input(INPUT_POST, "cpf"));
            
            $congregacao->setId(filter_input(INPUT_POST, "idCongregacao"));
            $membro->setCongregacao($congregacao);
            
            $funcao->setId(filter_input(INPUT_POST, "idFuncao"));
            $membro->setFuncao($funcao);

            $membro->setDataBatismo(filter_input(INPUT_POST, "dataBatismo"));
            $membro->setSituacao(filter_input(INPUT_POST, "situacao"));

            if (empty($idMembro)) {
                if ($membroDAO->cadastrar($membro)) {
                    unset($_REQUEST);
                    ?>
                    <script type="text/javascript">
                        mensagem(1, this.getUrl(), '');</script>
                    <?
                } else {
                    unset($_REQUEST);
                    ?>
                    <script type="text/javascript">
                        mensagem(2, this.getUrl(), '');
                    </script>
                    <?
                }
            } else {
                $membro->setId($idMembro);
                if ($membroDAO->editar($membro)) {
                    unset($_REQUEST);
                    ?>
                    <script type="text/javascript">
                        mensagem(1, this.getUrl(), '');
                    </script>
                    <?
                } else {
                    unset($_REQUEST);
                    ?>
                    <script type="text/javascript">
                        mensagem(2, this.getUrl(), '');
                    </script>
                    <?
                }
            }
        }
        ?>

        <div class="corpo">
            <? require SystemUtil::URL_SYSTEM . "pageComponents/header.php?idModulo=" . $idModulo ?>

            <div class="extras">
                <ul>
                    <li><?= DataUtil::imprimirDataAtual() ?></li>
                    <li>|</li>
                    <li>
                        <a href="../../sair.php" class="cursor">
                            <img src="../../public/images/icons/sair.png"/>
                            <label>Sair do sistema</label>
                        </a>
                    </li>
                </ul>
            </div> <br />

            <div class="modulos">
                <?= $moduloDAO->mostrarModulos($_SESSION["usuario"], TRUE) ?>
            </div>

            <div id="conteudo">

                <div class="menuEstrutural">
                    <a href="../../inicio.php">IN&Iacute;CIO</a>
                    ::
                    <a href="index.php?id=<?= $idModulo ?>"><?= $nomeModulo ?></a>
                    ::
                    <a href="formCadastrar.php?id=<?= $idModulo ?>">CADASTRAR</a>
                </div>

                <div class="oculto textoMedio" id="dialogAdicionarUsuario">
                    <br />
                    <form method="post" name="formAdicionarUsuario" id="formAdicionarUsuario" action="">
                        <input type="hidden" name="idMembro" id="idMembro" value="<?= $idCadastrado ?>"/>
                        <div class="negrito">
                            <label>Cargo:</label> &nbsp;
                            <?= CargoUtil::getSelect((!empty($membro->getCargo()) ? $membro->getCargo() : 0), "selectAdicionarUsuario") ?>
                        </div>
                    </form>
                </div>

                <div class="oculto textoMedio" id="dialogEditarUsuario">
                    <br />
                    <form method="post" name="formEditarUsuario" id="formEditarUsuario" action="">
                        <input type="hidden" name="idMembro" id="idMembro" value="<?= $idCadastrado ?>"/>
                        <div class="negrito">
                            <label>Cargo:</label> &nbsp;
                            <?= CargoUtil::getSelect((!empty($membro->getCargo()) ? $membro->getCargo() : 0), "selectEditarUsuario") ?>
                        </div>
                    </form>
                </div>

                <div class="oculto textoMedio textoCentro" id="dialogDesativarUsuario">
                    <p>
                        Tem certeza que quer desativar este usu&aacute;rio(a)?
                    </p>
                </div>

                <div class="oculto textoMedio textoCentro" id="dialogDesligarMembro">
                    <p>Tem certeza que quer desligar este membro?</p>
                </div>

                <? if (!empty($idCadastrado)) { ?>
                    <div class="modulo">
                        <div class="extrasConteudo">
                            <ul>
                                <li class="cursor">
                                    <a href="dizimosOfertas.php?id=<?= $idModulo ?>&idMembro=<?= $idCadastrado ?>" target="_blank">
                                        <img src="../../public/images/icons/adicionar.png" alt="" />
                                        <label title="Dizimos e Ofertas">DÍZIMOS E OFERTAS</label> 
                                    </a>
                                </li>
                                <li class="cursor">
                                    <a href="gerarCertificado.php?idMembro=<?= $idCadastrado ?>" target="_blank">
                                        <img src="../../public/images/icons/adicionar.png" alt="" />
                                        <label title="Gerar Certificado de Batismo">CERTIFICADO DE BATISMO</label>
                                    </a>
                                </li>
                                <li class="cursor">
                                    <a href="carta.php?idMembro=<?= $idCadastrado ?>&tipo=<?= CartaUtil::RECOMENDACAO ?>" 
                                        target="_blank">
                                        <img src="../../public/images/icons/adicionar.png" alt="" />
                                        <label title="Gerar Carta de Recomendação">
                                            <?= CartaUtil::getNomeCarta(CartaUtil::RECOMENDACAO) ?>
                                        </label>
                                    </a>
                                </li>
                                <li class="cursor">
                                    <a href="carta.php?idMembro=<?= $idCadastrado ?>&tipo=<?= CartaUtil::MUDANCA ?>" 
                                        target="_blank">
                                        <img src="../../public/images/icons/adicionar.png" alt="" />
                                        <label title="Gerar Carta de Mudança">
                                            <?= CartaUtil::getNomeCarta(CartaUtil::MUDANCA) ?>
                                        </label>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div> <br />
                <? } ?>

                <form method="post" name="formCadastrar" id="formCadastrar" action="">
                    <input type="hidden" name="idModulo" id="idModulo" value="<?= $idModulo ?>"/>
                    <input type="hidden" name="idMembro" id="idMembro" value="<?= $idCadastrado ?>"/>
                    <input type="hidden" name="situacao" id="situacao" value="<?= $membro->getSituacao() ?>"/>
                    <div class="formulario">
                        <div class="campo">
                            <label class="negrito">Nome:</label> &nbsp;
                            <input type="text" name="nome" id="nome" maxlength="255" size="100" class="caixaAlta retiraAcento focus"
                                value="<?= $membro->getNome() ?>"/>
                        </div>        
                        <br />
                        <div class="campo">
                            <label class="negrito">Rua/Avenida:</label> &nbsp;
                            <input type="text" name="rua" id="rua" maxlength="70" size="40" class="caixaAlta retiraAcento" 
                                value="<?= $membro->getRua() ?>"/> &nbsp;
                            <label class="negrito">Bairro:</label> &nbsp;
                            <input type="text" name="bairro" id="bairro" maxlength="70" size="40" class="caixaAlta retiraAcento" 
                                value="<?= $membro->getBairro() ?>"/> &nbsp;
                            <label class="negrito">Complemento:</label> &nbsp;
                            <input type="text" name="complemento" id="complemento" maxlength="70" size="40" class="caixaAlta retiraAcento" 
                                value="<?= $membro->getComplemento() ?>"/>
                        </div>
                        <br />
                        <div class="campo">
                            <?php 
                                if (isset($idCadastrado)) {
                            ?>
                                <script type="text/javascript">
                                    selectCidades(<?= !empty($membro->getCidade()->getEstado()) ? $membro->getCidade()->getEstado()->getId() : 0 ?>, 
                                                <?= !empty($membro->getCidade()) ? $membro->getCidade()->getId() : 0 ?>);
                                </script>
                            <?php
                                }
                            ?>
                            <label class="negrito">Estado:</label> &nbsp;
                            <?= 
                                $estadoDAO->getSelect(!empty($membro->getCidade()->getEstado()) ? $membro->getCidade()->getEstado()->getId() : 0) 
                            ?> &nbsp;
                            <label class="negrito">Cidade:</label> &nbsp;
                            <select name="idCidade" id="idCidade">
                                <option value="">SELECIONE UMA CIDADE</option>
                            </select>
                        </div>
                        <br />
                        <div class="campo">
                            <label class="negrito">Telefone:</label> &nbsp;
                            <input type="text" name="fone" class="maskFone" id="fone" maxlength="15" size="10" 
                                value="<?= $membro->getFone() ?>"/> &nbsp;
                            <label class="negrito">Data de nascimento:</label> &nbsp;
                            <input type="text" name="dataNascimento" class="maskDate" id="dataNascimento" maxlength="10" size="10" 
                                value="<?= $membro->getDataNascimento() ?>"/> &nbsp;
                            <label class="negrito">Sexo:</label> &nbsp;
                            <?= SexoUtil::getSelect($membro->getSexo()) ?> &nbsp;
                            <label class="negrito">Estado Civil:</label> &nbsp;
                            <?= EstadoCivilUtil::getSelect($membro->getEstadoCivil()) ?>
                        </div>
                        <br />
                        <div class="campo">
                            <label class="negrito">Naturalidade:</label> &nbsp;
                            <input type="text" name="naturalidade" id="naturalidade" maxlength="70" size="30" class="caixaAlta retiraAcento" 
                                value="<?= $membro->getNaturalidade() ?>" placeholder="SAO LUIS/MA"/> &nbsp;
                            <label class="negrito">RG:</label> &nbsp;
                            <input type="text" name="rg" id="rg" maxlength="11" size="20" value="<?= $membro->getRg() ?>"/> &nbsp;
                            <label class="negrito">CPF:</label> &nbsp;
                            <input type="text" name="cpf" id="cpf" maxlength="11" size="20" value="<?= $membro->getCpf() ?>"/>
                        </div>
                        <br />
                        <div class="campo">
                            <label class="negrito">Congrega&ccedil;&atilde;o:</label> &nbsp;
                            <?= 
                                $congregacaoDAO->getSelect(
                                    !empty($membro->getCongregacao()) ? $membro->getCongregacao()->getId() : 0
                                ); 
                            ?> &nbsp;
                            <label class="negrito">Fun&ccedil;&atilde;o:</label> &nbsp;
                            <?= 
                                $funcaoDAO->getSelect(
                                    !empty($membro->getFuncao()) ? $membro->getFuncao()->getId() : 0
                                ); 
                            ?> &nbsp;
                            <label class="negrito">Data de batismo:</label> &nbsp;
                            <input type="text" name="dataBatismo" class="maskDate" id="dataBatismo" maxlength="10" size="10" 
                                value="<?= $membro->getDataBatismo() ?>"/>
                        </div>
                        <br />

                        <?
                            $btnCadastrar = "Cadastrar";
                            if (!empty($membro->getId())) {
                                $btnCadastrar = "Editar";
                        ?>
                                <div class="campo">
                                    <label class="negrito">Situa&ccedil;&atilde;o:</label> 
                                    <label class="italico"><?= $membro->getNomeSituacao() ?></label> <br /> 
                                    <label class="negrito">Usuário Cadastro:</label> 
                                    <label class="italico"><?= $usuarioCadastro->getNome() ?></label> <br />
                                    <label class="negrito">Data Cadastro:</label> 
                                    <label class="italico"><?= $membro->getDataCadastro() ?></label>
                                </div> <br />
                                <div class="btnsCadastrar">
                                    <input type="submit" name="btnCadastrar" value="<?= $btnCadastrar ?>"/>
                                    <? if ($membro->getSituacao() === Membro::LIGADO) { ?>
                                        <input type="button" name="btnDesligar" id="btnDesligar" value="Desligar"/>
                                    <? } ?>
                                <? if ($membroDAO->isUsuario($membro->getId())) { ?>
                                    <input type="button" name="btnEditarUsuario" id="btnEditarUsuario" value="Editar Usuário"/>
                                    <input type="button" name="btnDesativarUsuario" id="btnDesativarUsuario" value="Desativar Usuário"/>
                                <? } else { ?>
                                    <input type="button" name="btnAdicionarUsuario" id="btnAdicionarUsuario" value="Adicionar Usuário"/>
                                <?
                                }
                                ?>
                        <?  } else { ?>
                                <div class="btnsCadastrar">
                                    <input type="submit" name="btnCadastrar" value="Cadastrar"/>
                                </div>
                        <?  } ?>
                        </div>
                    </div>
                </form> <br /> <br /> <br />
            </div>
        </div>
    </body>
</html>