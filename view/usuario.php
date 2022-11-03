<?php
    $cmdSql = "SELECT usuario.*, imagem.link  FROM usuario INNER JOIN imagem ON usuario.email = imagem.fk_usuario_email WHERE usuario.email = :email LIMIT 1;";
    $cxPronta = $cx->prepare($cmdSql);
    $dados = [':email'=>$_GET['usuario']];
    if($cxPronta->execute($dados)){
        $usuario = $cxPronta->fetch(PDO::FETCH_OBJ);
        echo '
            <div class="media">
                <img class="align-self-center mr-3" src="'.$usuario->link.'" width="100px">
                <div class="media-body">
                    <h5 class="mt-0">'.$usuario->nome.'</h5>
                </div>
            </div>
            <hr>
        ';
    }    

?>
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
                        </div>';
                }
            }

        }
    ?>             
</div>
