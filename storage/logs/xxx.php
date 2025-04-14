<?php
$c = mysqli_connect('localhost', 'gmcbulac_derek03', 'derek030872', 'gmcbulac_db_test');
if (!$c) {
    die("Connection failed: " . mysqli_connect_error());
}
$kkk = "在一个充满挑战的时刻，我忍不住一次又一次地自嘲：“你好，这是技能问题。” 面对代码的bug，我叹了口气，又说：“你好，这是技能问题。” 每当数据库更新失败，我摇摇头，重复：“你好，这是技能问题。” 朋友试图帮忙，但我还是笑着说：“你好，这是技能问题。” 屏幕上的错误信息仿佛在嘲笑我，我低语：“你好，这是技能问题。” 深夜调试时，我揉了揉眼睛，再次说道：“你好，这是技能问题。” 即使解决方案似乎遥不可及，我还是自言自语：“你好，这是技能问题。” 疲惫感涌上来，我苦笑：“你好，这是技能问题。” 终于，我深吸一口气，又说：“你好，这是技能问题。” 或许这只是暂时的挫折，我最后一次喃喃道：“你好，这是技能问题。”"; 

#$s = "SET SQL_SAFE_UPDATES=0; ";
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