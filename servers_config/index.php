<!DOCTYPE html>
<html>
<head>
  <title>Sysinfo</title>
</head>
<body>
  <style>
    body {  }
    .crit { color: red; }
    .warn { color: yellow; }
    table {
      float: left;
      margin: 5px;
      padding: 15px;
      border: 1px solid black; 
    }
    div { 
      content: "";
      clear: both;
      display: table; 
      float: left;
    }
  </style>
 
  <div>
    <table>
      <tr><td>Адрес клиента</td><td><?= $_SERVER["HTTP_X_REAL_IP"] ?></td></tr>
      <tr><td>Порт клиента</td><td><?= $_SERVER["HTTP_X_REAL_PORT"] ?></td></tr>
      <tr><td>Адрес nginx</td><td><?= $_SERVER["REMOTE_ADDR"] ?></td></tr>
      <tr><td>Порт nginx</td><td><?= $_SERVER["REMOTE_PORT"] ?></td></tr>
      <tr><td>Версия nginx</td><td><?= $_SERVER["HTTP_X_NGX_VERSION"] ?></td></tr>
    </table>
  </div>
  
  <div>
    <?php
      $cores = exec('cat /proc/cpuinfo | awk -F ":" \'/cpu cores/{print $2}\'');
      function getClassByValue($val) {
        if ($val >= 90) return "crit";
        else if ($val >= 80) return "warn";
        else return "";
      }
    ?>

    <table>
      <caption>Средняя загрузка</caption>
      <?php if($f = fopen("/var/log/balinux/loadavg", "r")) { ?>
      <tr>
        <th>1 min</th><th>5 min</th><th>15 min</th>
      </tr>
      <tr><?php foreach (fscanf($f, "%s\t%s\t%s\n") as $field) { ?>
        <td class="<?= getClassByValue($field/$cores * 100) ?>"><?= $field ?></td>
      <?php } fclose($f); } ?></tr>
    </table>

    <table>
      <caption>Средняя загрузка CPU</caption>
      <?php if($f = fopen("/var/log/balinux/mpstat", "r")) { ?>
      <tr>
        <th>%us(%us+%ni)</th><th>%sys</th>
        <th>%id</th><th>%iowait</th>
      </tr>
      <?php while (!feof($f)) { ?>
        <tr><?php list ($f1, $f2, $f3, $f4) = fscanf($f, "%f\t%f\t%f\t%f\n"); ?>
          <td class=<?= getClassByValue($field) ?>><?= $f1 ?></td>
          <td class=<?= getClassByValue($field) ?>><?= $f2 ?></td>
          <td class=<?= getClassByValue($field) ?>><?= $f3 ?></td>
          <td class=<?= getClassByValue($field) ?>><?= $f4 ?></td>
        </tr>
      <?php } ?>
      <?php fclose($f); } ?>
    </table>
  </div>

  <div>
    <table>
      <caption>Загрузка дисков</caption>
      <?php if($f = fopen("/var/log/balinux/iostat", "r")) { ?>
      <tr>
        <th>Device</th>
        <th>r/s</th><th>w/s</th><th>rkB/s</th>
        <th>wkB/s</th><th>await</th><th>%util</th>
      </tr>
      <?php while (!feof($f)) { ?>
        <tr><?php list ($f1, $f2, $f3, $f4, $f5, $f6, $f7) = fscanf($f, "%s\t%s\t%s\t%s\t%s\t%s\t%s\n"); ?>
          <td><?= $f1 ?></td>
          <td><?= $f2 ?></td>
          <td><?= $f3 ?></td>
          <td><?= $f4 ?></td>
          <td><?= $f5 ?></td>
          <td><?= $f6 ?></td>
          <td class=<?= getClassByValue($f7) ?>><?= $f7 ?></td>
        </tr>
      <?php } ?>
      <?php fclose($f); } ?>
    </table>

    <table>
      <caption>Информация о файловых системах</caption>
      <?php if($f = fopen("/var/log/balinux/df", "r")) { ?>
      <tr>
        <th>Filesystem</th><th>Avail%</th>
        <th>IAvail%</th><th>Mounted on</th>
      </tr>
      <?php while (!feof($f)) { ?>
        <tr><?php list ($f1, $f2, $f3, $f4) = fscanf($f, "%s\t%s\t%s\t%s\n"); ?>
          <td><?= $f1 ?></td>
          <td class=<?= getClassByValue(100-$f2) ?>><?= $f2 ?></td>
          <td class=<?= getClassByValue(100-$f3) ?>><?= $f3 ?></td>
          <td><?= $f4 ?></td>
        </tr>
      <?php } ?>
      <?php fclose($f); } ?>
    </table>
  </div>

  <div>
    <table>
      <caption>Top talkers по протоколам</caption>
      <?php if ($f = fopen("/var/log/balinux/proto.log", "r")) { ?>
      <tr>
        <th>protocol</th><th>bytes</th><th>%</th>
      </tr>
      <?php while (!feof($f)) { ?>
        <tr><?php foreach (fscanf($f, "%s\t%s\t%s\n") as $field) { ?>
          <td><?= $field ?></td>
        <?php } ?></tr>
      <?php } ?>
      <?php fclose($f); } ?>
    </table>

    <table>
      <caption>Top talkers по пакетам</caption>
      <?php if($f = fopen("/var/log/balinux/pps.log", "r")) { ?>
      <tr>
        <th>source</th><th>destination</th>
        <th>protocol</th><th>pps</th>
      </tr>
      <?php while (!feof($f)) { ?>
        <tr><?php foreach (fscanf($f, "%s\t%s\t%s\t%s\n") as $field) { ?>
          <td><?= $field ?></td>
        <?php } ?></tr>
      <?php } ?>
      <?php fclose($f); } ?>
    </table>

    <table>
      <caption>Top talkers по трафику</caption>
      <?php  if($f = fopen("/var/log/balinux/bps.log", "r")) { ?>
      <tr>
        <th>source</th><th>destination</th>
        <th>protocol</th><th>bps</th>
      </tr>
      <?php while (!feof($f)) { ?>
        <tr><?php foreach (fscanf($f, "%s\t%s\t%s\t%s\n") as $field) { ?>
          <td><?= $field ?></td>
        <?php } ?></tr>
      <?php } ?>
      <?php fclose($f); } ?>
    </table>
  </div>
  
  <div>
    <table>
      <caption>Слущающие сокеты</caption>
      <?php if ($f = fopen("/var/log/balinux/sockl", "r")) { ?>
      <tr>
        <th>TCP</th>
        <th>UDP</th>
      </tr>
      <tr><?php while (!feof($f)) { ?>
        <td><?= fgets($f) ?></td>
      <?php } ?>
      </tr>
      <?php fclose($f); } ?>
    </table>

    <table>
      <caption>TCP сокеты</caption>
      <?php if ($f = fopen("/var/log/balinux/sockstat", "r")) { ?>
      <tr>
        <th>Состояние</th>
        <th>Количество</th>
      </tr>
      <?php while (!feof($f)) { ?>
        <tr><?php foreach (fscanf($f, "%s\t%s\n") as $field) { ?>
          <td><?= $field ?></td>
        <?php } ?></tr>
      <?php } ?>
      <?php fclose($f); } ?>
    </table>
  </div>
  
  <div>
    <table>
      <caption>Загрузка сети</caption>
      <?php if($f = fopen("/var/log/balinux/netload", "r")) { ?>
      <tr>
        <th>Interface</th>
        <th colspan="8">Received</th>
        <th colspan="8">Transmitted</th>
      </tr>
      <tr>
        <th></th>
        <th>bytes</th><th>packets</th><th>errs</th><th>drop</th>
        <th>fifo</th><th>frame</th><th>compressed</th><th>multicast</th>
        <th>bytes</th><th>packets</th><th>errs</th><th>drop</th>
        <th>fifo</th><th>colls</th><th>carriers</th><th>compressed</th> 
      </tr>
      <?php while (!feof($f)) { ?>
        <tr><?php foreach (fscanf($f, "%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\n") as $field) { ?>
          <td><?= $field ?></td>
        <?php } ?></tr>
      <?php } ?>
      <?php fclose($f); } ?>
    </table>
  </div>
</body>
</html>
