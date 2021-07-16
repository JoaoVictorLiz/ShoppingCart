<?php
session_start();

//$connect = mysqli_connect("localhost", "root", "292216", "shopping_cart");
include_once('config.php');
if(isset($_POST['add_to_cart'])){

    if(isset($_SESSION['cart'])){
        $session_array_id = array_column($_SESSION['cart'], "id");

        if(!in_array($_GET['id'], $session_array_id)){
            $session_array = array(
                'id' => $_GET['id'],
                "nome" => $_POST['nome'],
                "preco" => $_POST['preco'],
                "quantity" => $_POST['quantity']
            );
    
            $_SESSION['cart'][] = $session_array; 
        }
    } else {
        $session_array = array(
            'id' => $_GET['id'],
            "nome" => $_POST['nome'],
            "preco" => $_POST['preco'],
            "quantity" => $_POST['quantity']
        );

        $_SESSION['cart'][] = $session_array;
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Shopping Cart</title>
</head>
<body>
    <div class="container-fluid">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="text-center">Carrinho de Compras</h2>
                    <div class="col-md-12">
                    <div class="row">

                    
                    <?php
                        $query = "SELECT *FROM cart_item";
                        $result = mysqli_query($connect,$query);

                        while($row = mysqli_fetch_array($result)){?>
                        <div class="col-md-4">
                            <form method="post" action="index.php?id=<?= $row['id'] ?>">
                            <img src="img/<?= $row['image'] ?>" style='height: 150px;' >
                            <h5 class="text-center"><?= $row['nome']; ?></h5>
                            <h5 class="text-center">R$<?= number_format($row['preco'],2); ?></h5>
                            <input type="hidden" name="nome" value="<?= $row['nome'] ?>">
                            <input type="hidden" name="preco" value="<?= $row['preco'] ?>">
                            <input type="number" name="quantity" value="1" class="form-control">
                            <input type="submit" name="add_to_cart" class="btn btn-warning btn-block my-2" value="Adicionar ao Carrinho">

                            </form>
                        </div>
                        <?php }

                    ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h2 class="text-center">Produto Selecionado</h2>

                    <?php 

                    $total = 0;
                    
                    $output = "";
                    
                    $output .= "
                    <table class='table table-bordered table-striped'>
                         <tr>
                            <th>ID</th>
                            <th>Nome Produto</th>
                            <th>Preço Produto</th>
                            <th>Quantidade</th>
                            <th>Preço Total</th>
                            <th>Ação</th>
                            </tr>   
                    ";

                    if(!empty($_SESSION['cart'])){

                        foreach($_SESSION['cart'] as $key => $value){
                            
                            $output .= "
                              <tr>
                                <td>".$value['id']."</td>
                                <td>".$value['nome']."</td>  
                                <td>".$value['preco']."</td>  
                                <td>".$value['quantity']."</td>  
                                <td>R$".number_format($value['preco'] * $value['quantity'],2)."</td>  
                                <td>
                                    <a href='index.php?action=remove&id=".$value['id']."'>
                                        <button class='btn-danger btn-block'>Remover</button>
                                    </a>
                                </td>    
                            ";

                            $total = $total + $value['quantity'] * $value['preco'];
                        }

                        $output .= "
                            <tr>
                                <td colspan='3'></td>
                                <td><b>Total Price</b></td>
                                <td>".number_format($total,2)."</td>
                                <td>
                                    <a href='index.php?action=clearall'>
                                        <button class='btn btn-warning btn-block'>Limpar Tudo</button>
                                    </a>
                                </td>
                            </tr>
                        ";
                    }




                    echo $output;
                    ?>

                </div> 
            </div>
        </div>
    </div>

    <?php

        if(isset($_GET['action'])){
            
            if($_GET['action'] == "clearall"){
                unset($_SESSION['cart']);
            }

            if($_GET['action'] == "remove"){
                foreach($_SESSION['cart'] as $key => $value){
                    if($value['id'] == $_GET['id']){
                        unset($_SESSION['cart'][$key]);
                    }
                }
            }
        }                    

    ?>
</body>
</html>