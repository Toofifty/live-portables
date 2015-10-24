<?php
    session_start();
    
    if (isset($_GET['id'])) {
        // database connections
        $db = "db/data.db";
        $con = new PDO('sqlite:' . $db);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        
        $id = $_GET['id'];
    
        $q = "SELECT * FROM portables WHERE id=$id";
        $sth = $con->prepare($q);
        $sth->execute();
        
        $p = $sth->fetch();
        
        if (in_array($id, $_SESSION['owned']) || $p->downs >= 9) {
            // remove
            $q = "DELETE FROM portables WHERE id=$id";
            $sth = $con->prepare($q);
            $sth->execute();
            echo "<p>Removal successful. Redirecting in 2 seconds...</p>";
        } else {
            
            if (in_array($id, $_SESSION['voted'])) {
                echo "<p>You've already voted on that portable. Redirecting in 5 seconds...</p>";
                echo "<meta http-equiv='refresh' content='5; url=index.php' />";
            } else {
        
                array_push($_SESSION['voted'], $id);
                // downvote
                $d = $p->downs + 1;
                $q = "UPDATE portables SET downs=$d WHERE id=$id";
                $sth = $con->prepare($q);
                $sth->execute();
                echo "<p>Vote successful. Redirecting in 2 seconds...</p>";
            }
        }
        echo "<meta http-equiv='refresh' content='2; url=index.php?p=".$p->type."' />";
    } else {
        echo "<p>Invalid data. Redirecting in 5 seconds...</p>";
        echo "<meta http-equiv='refresh' content='5; url=index.php' />";
    }
?>