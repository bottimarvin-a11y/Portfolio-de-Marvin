<body>
<html>
<?php
    @include("connexion.php");
    $a=$_POST["login"];
    $b=$_POST["mdp"];
   
    $requete= "SELECT * from users where login= '$a'  and mdp= '$b' ";   

    $resultat=mysqli_query($conn, $requete);
    

    $ligne=mysqli_num_rows($resultat);
    $enreg=mysqli_fetch_array($resultat);
     
   
    if ($ligne==1)
       
              
        header("location:menu.html");
        
        
   else
       header("location:index.php");
  
?>
</body>
</html>
