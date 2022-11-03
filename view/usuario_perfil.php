<?php
$usuario = $_SESSION['user_logado'];
require_once 'Upload.php';
require_once 'Conexao.php';
if(isset($_POST['btnImg'])){
    $up = new Upload($_FILES['foto'],'img/');
    $url_img = $up->salvarImagem();

    $cmdSql = "INSERT INTO imagem(link, fk_usuario_email) VALUES (:url_img, :email)";
    $dados = [
        ':email' => $usuario->email,
        ':url_img' => $url_img
    ];
    $cxPronta = $cx->prepare($cmdSql); 
    if($cxPronta->execute($dados)){
        echo'<div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Cadastro</h4>
            <p>Imagem cadastrada com sucesso</p>
        </div>';
    }
    else{
        echo'<div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">Cadastro</h4>
            <p>Erro ao cadastrar imagem</p>
        </div>';
    }     
        
}
// Deletar imagem
if(isset($_POST['btnDelete'])){
    $cmdSql = 'CALL imagem_excluir(:link)';
    $link = $_POST['btnDelete'];
    
    $cxPreparado = $cx->prepare($cmdSql);
    if(!$cxPreparado->execute([':link'=>$link])){
        echo'<div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">Exclusão de img</h4>
            <p>Erro ao deletar imagem</p>
        </div>';
    }
}

if(isset($_POST['btnExcluirUser'])){
    $cmdSql = 'CALL usuario_excluir(:email)';
    $email = $_POST['btnExcluirUser'];
    
    $cxPreparado = $cx->prepare($cmdSql);
    if(!$cxPreparado->execute([':email'=>$email])){
        echo'<div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">Exclusão de usuário</h4>
            <p>Erro ao deletar usuário</p>
        </div>';
    }
}

?>
<div class="container">
    <h4 class="text-secondary">E aí <?php echo $usuario->nome; ?>, que tal postar suas fotos favoritas?</h4 class="text-secondary">

    <form method="POST" class="form-inline" enctype="multipart/form-data"> 
        <input type="file" class="form-control" name="foto" >            
        <button type="submit" name="btnImg" class="btn btn-primary form-control">Enviar IMG</button>
    </form>

    <fieldset>
        <legend>Minha fotos</legend>
        <div class="card-columns">
            <?php
                $cmdSql = "SELECT * FROM imagem WHERE imagem.fk_usuario_email = :email";
                $cxPronta = $cx->prepare($cmdSql); 
                if($cxPronta->execute([':email'=>$usuario->email])){
                    if($cxPronta->rowCount() > 0){
                        $fotos = $cxPronta->fetchAll(PDO::FETCH_OBJ);
                        foreach ($fotos as $foto) {
                            echo'<div class="card">
                                    <img class="card-img-top" src="'.$foto->link.'">
                                    <form method="post">
                                        <button 
                                            type="submit"
                                            value="'.$foto->link.'"
                                            name="btnDelete"
                                        >DELETE</button>
                                    </form>
                                </div>';
                        }
                    }
                }
            ?>             
        </div>

    </fieldset>
</div>
