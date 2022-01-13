<?php
session_start();
// bikin koneksi
$conn = mysqli_connect('localhost','root','','kasir');
// login
if(isset($_POST['login'])){
    //initiate variable
    $username = $_POST['username'];
    $password = $_POST['password'];

    $check = mysqli_query($conn, "select * from user where username='$username' and password='$password'");
    $hitung = mysqli_num_rows($check);

    if($hitung>0){
        //jika datanya ditemukan
        //berhasil login

        $_SESSION['login'] = 'True';
        header('location:index.php');
    }
    else {
        //data tidak ditemukan
        //gagal login
        echo '<script>alert("Username atau Password salah");
        window.location.href="login.php"</script>';
    }
}

if(isset($_POST['tambahbarang'])){
    $namaproduk = $_POST['namaproduk'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];
    $harga = $_POST['harga'];

    $insert = mysqli_query($conn, "insert into produk (namaproduk, deskripsi, harga, stock) values ('$namaproduk', '$deskripsi', '$harga', '$stock')");

    if($insert){
        header('location:stock.php');
    }
    else {
    echo '<script>alert("gagal menambah barang baru");
        window.location.href="stock.php"</script>';
    }
}

if(isset($_POST['tambahpelanggan'])){
    $namapelanggan = $_POST['namapelanggan'];
    $notelp = $_POST['notelp'];
    $alamat = $_POST['alamat'];
    
    $insert = mysqli_query($conn, "insert into pelanggan (namapelanggan, notelp,alamat) values ('$namapelanggan', '$notelp', '$alamat')");

    if($insert){
        header('location:pelanggan.php');
    }
    else {
    echo '<script>alert("gagal menambah pelanggan baru");
        window.location.href="pelanggan.php"</script>';
    }
}

if(isset($_POST['tambahpesanan'])){
    $idpelanggan = $_POST['idpelanggan'];
    
    $insert = mysqli_query($conn, "insert into pesanan (idpelanggan) values ('$idpelanggan')");

    if($insert){
        header('location:index.php');
    }
    else {
    echo '<script>alert("gagal menambah pesanan baru");
        window.location.href="index.php"</script>';
    }
}

if(isset($_POST['addproduk'])){
    $idproduk = $_POST['idproduk'];
    $idp = $_POST['idp'];
    $qty = $_POST['qty'];
    
   $hitung1 = mysqli_query($conn, "select * from produk where idproduk='$idproduk'");
   $hitung2 = mysqli_fetch_array($hitung1);
   $stocksekarang = $hitung2['stock'];

   if($stocksekarang>=$qty){
       $selisih = $stocksekarang-$qty;
       $insert = mysqli_query($conn, "insert into detailpesanan (idpesanan,idproduk,qty) values ('$idp','$idproduk','$qty')");
       $update = mysqli_query($conn, "update produk set stock='$selisih' where idproduk='$idproduk'");
       if($insert&&$update){
           header('location:view.php?idp='.$idp);
        }  
        else {
        echo '<script>alert("gagal menambah pesanan baru");
            window.location.href="view.php?idp='.$idp.'"</script>';
        }
}
    else {
        echo '<script>alert("stock barang tidak cukup");
        window.location.href="view.php?idp='.$idp.'"</script>';
    }
}

    
if(isset($_POST['barangmasuk'])){
    $idproduk = $_POST['idproduk'];
    $qty = $_POST['qty'];

    $caristock = mysqli_query($conn, "select * from produk where idproduk='$idproduk'");
    $caristock2 = mysqli_fetch_array($caristock);
    $stocksekarang = $caristock2['stock'];

    $newstock = $stocksekarang+$qty;

    $insertb = mysqli_query($conn, "insert into masuk (idproduk,qty) values('$idproduk','$qty')");
    $updatetb = mysqli_query($conn, "update produk set stock='$newstock' where idproduk='$idproduk'");

    if($insertb&&$updatetb){
        header('location:masuk.php');
    }
    else {
        echo '<script>alert("gagal");
            window.location.href="masuk.php"</script>';
    }
}

if(isset($_POST['hapusprodukpesanan'])){
    $idp = $_POST['idp'];
    $idpr = $_POST['idpr'];
    $idorder = $_POST['idorder'];

    $cek1 = mysqli_query($conn, "select * from detailpesanan where iddetailpesanan='$idp'");
    $cek2 = mysqli_fetch_array($cek1);
    $qtysekarang = $cek2['qty'];

    $cek3 = mysqli_query($conn, "select * from produk where idproduk='$idpr'");
    $cek4 = mysqli_fetch_array($cek3);
    $stocksekarang = $cek4['stock'];

    $hitung = $stocksekarang+$qtysekarang;

    $update = mysqli_query($conn, "update produk set stock='$hitung' where idproduk='$idpr'");
    $hapus = mysqli_query($conn, "delete from detailpesanan where idproduk='$idpr' and iddetailpesanan='$idp'");

    if($update&&$hapus){
        header('location:view.php?idp='.$idorder);  
    }
    else {
        echo '<script>alert("gagal menghapus barang");
        window.location.href="view.php?idp='.$idorder.'"</script>';
    }

}

if(isset($_POST['editbarang'])){
    $np = $_POST['namaproduk'];
    $desc = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $idp = $_POST['idp'];

    $query = mysqli_query($conn, "update produk set namaproduk='$np', deskripsi='$desc', harga='$harga' where idproduk='$idp'");

    if($query){
        header('location:stock.php');
    }
    else {
        echo '<script>alert("gagal");
        window.location.href="stock.php"</script>';
    }
}

if(isset($_POST['hapusbarang'])){
    $idp = $_POST['idp'];

    $query = mysqli_query($conn, "delete from produk where idproduk='$idp'");

    if($query){
        header('location:stock.php');
    }
    else {
        echo '<script>alert("gagal");
        window.location.href="stock.php"</script>';
    }
}

if(isset($_POST['editpelanggan'])){
    $npl = $_POST['namapelanggan'];
    $notelp = $_POST['notelp'];
    $alamat = $_POST['alamat'];
    $idpl = $_POST['idpl'];

    $query = mysqli_query($conn, "update pelanggan set namapelanggan='$npl', notelp='$notelp', alamat='$alamat' where idpelanggan='$idpl'");

    if($query){
        header('location:pelanggan.php');
    }
    else {
        echo '<script>alert("gagal");
        window.location.href="pelanggan.php"</script>';
    }
}

if(isset($_POST['hapuspelanggan'])){
    $idpl = $_POST['idpl'];

    $query = mysqli_query($conn, "delete from pelanggan where idpelanggan='$idpl'");

    if($query){
        header('location:pelanggan.php');
    }
    else {
        echo '<script>alert("gagal");
        window.location.href="pelanggan.php"</script>';
    }
}

if(isset($_POST['editdatabarangmasuk'])){
    $qty = $_POST['qty'];
    $idm = $_POST['idm'];
    $idp = $_POST['idp'];    

    $cari =mysqli_query($conn, "select * from masuk where idmasuk='$idm'");
    $cari2 = mysqli_fetch_array($cari);
    $qtysekarang = $cari2['qty'];

    $caristock = mysqli_query($conn, "select * from produk where idproduk='$idp'");
    $caristock2 = mysqli_fetch_array($caristock);
    $stocksekarang = $caristock2['stock'];

    if($qty >= $qtysekarang){
        $selisih = $qty-$qtysekarang;
        $newstok = $stocksekarang+$selisih;
        $query1 = mysqli_query($conn, "update masuk set qty='$qty' where idmasuk='$idm'");
        $query2 = mysqli_query($conn, "update produk set stock='$newstok' where idproduk='$idp'");
        if($query1&&$query2){
        header('location:masuk.php');
        }
        else {
        echo '<script>alert("gagal");
            window.location.href="masuk.php"</script>';
        }
    }
    else {
        $selisih = $qtysekarang-$qty;
        $newstok = $stocksekarang-$selisih;
        $query1 = mysqli_query($conn, "update masuk set qty='$qty' where idmasuk='$idm'");
        $query2 = mysqli_query($conn, "update produk set stock='$newstok' where idproduk='$idp'");
        
        if($query1&&$query2){
        header('location:masuk.php');
        }
        else {
        echo '<script>alert("gagal");
            window.location.href="masuk.php"</script>';
        }
    }
}

if(isset($_POST['hapusdatabarangmasuk'])){
    $idm = $_POST['idm'];
    $idp = $_POST['idp'];

    $cari =mysqli_query($conn, "select * from masuk where idmasuk='$idm'");
    $cari2 = mysqli_fetch_array($cari);
    $qtysekarang = $cari2['qty'];

    $caristock = mysqli_query($conn, "select * from produk where idproduk='$idp'");
    $caristock2 = mysqli_fetch_array($caristock);
    $stocksekarang = $caristock2['stock'];

        
        $newstok = $stocksekarang-$qtysekarang;
        $query1 = mysqli_query($conn, "delete from masuk where idmasuk='$idm'");
        $query2 = mysqli_query($conn, "update produk set stock='$newstok' where idproduk='$idp'");
        
        if($query1&&$query2){
        header('location:masuk.php');
        }
        else {
        echo '<script>alert("gagal");
            window.location.href="masuk.php"</script>';
        }
}

if(isset($_POST['hapusorder'])){
    $ido = $_POST['ido'];

    $cekdata = mysqli_query($conn, "select * from detailpesanan dp where idpesanan='$ido'");

    while($ok = mysqli_fetch_array($cekdata)){
        $qty = $ok['qty'];
        $idproduk = $ok['idproduk'];
        $iddp = $ok['iddetailpesanan'];

        $caristock = mysqli_query($conn, "select * from produk where idproduk='$idproduk'");
        $caristock2 = mysqli_fetch_array($caristock);
        $stocksekarang = $caristock2['stock'];

        $newstock = $stocksekarang+$qty;

        $queryupdate = mysqli_query($conn, "update produk set stock='$newstok' where idproduk='$idproduk'");

        $querydelete = mysqli_query($conn, "delete from detailpesanan where iddetailpesanan='$iddp'");

    }

    $query = mysqli_query($conn, "delete from pesanan where idorder='$ido'");

    if($queryupdate&&$querydelete&&$query){
        header('location:index.php');
    }
    else {
        echo '<script>alert("gagal");
        window.location.href="index.php"</script>';
    }
}

if(isset($_POST['editdetailpesanan'])){
    $qty = $_POST['qty'];
    $iddp = $_POST['iddp'];
    $idpr = $_POST['idpr'];
    $idp = $_POST['idp'];    

    $cari = mysqli_query($conn, "select * from detailpesanan where iddetailpesanan='$iddp'");
    $cari2 = mysqli_fetch_array($cari);
    $qtysekarang = $cari2['qty'];

    $caristock = mysqli_query($conn, "select * from produk where idproduk='$idpr'");
    $caristock2 = mysqli_fetch_array($caristock);
    $stocksekarang = $caristock2['stock'];

    if($qty >= $qtysekarang){
        $selisih = $qty-$qtysekarang;
        $newstok = $stocksekarang-$selisih;
        $query1 = mysqli_query($conn, "update detailpesanan set qty='$qty' where iddetailpesanan='$iddp'");
        $query2 = mysqli_query($conn, "update produk set stock='$newstok' where idproduk='$idpr'");
        if($query1&&$query2){
        header('location:view.php?idp='.$idp);
        }
        else {
        echo '<script>alert("gagal");
            window.location.href="view.php?idp='.$idp.'"</script>';
        }
    }
    else {
        $selisih = $qtysekarang-$qty;
        $newstok = $stocksekarang+$selisih;
        $query1 = mysqli_query($conn, "update detailpesanan set qty='$qty' where iddetailpesanan='$iddp'");
        $query2 = mysqli_query($conn, "update produk set stock='$newstok' where idproduk='$idpr'");
        
        if($query1&&$query2){
        header('location:view.php?idp='.$idp);
        }
        else {
        echo '<script>alert("gagal");
            window.location.href="view.php?idp='.$idp.'"</script>';
        }
    }
}
?>