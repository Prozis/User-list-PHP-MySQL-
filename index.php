<!DOCTYPE html>

<html lang="ru" dir="ltr">
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="/css/style.css">
  <title>Главная страница</title>
</head>
<body>
  <?php require_once 'header.php'; ?>
  <div class="container">
    <div class="users_list">

      <?php
      require_once '/core/functions.php';
      /* Подключение к серверу MySQL */
      $link = connect();
      //количество записей в таблице
      $count = count_rows($link);
      $count_page = ceil($count/10); //количество страниц  при выводе по 10, округление вверх
      echo "<h3>Список пользователей (всего: $count)</h3>";
      // вывод по 10 строк номер страницы из GET
      $page = 0;
      if(isset($_GET['page'])){
        $page = $_GET['page'];
      }

      $query = 'SELECT * FROM users ORDER BY id LIMIT 10 OFFSET '.$page*10;
      if ($result = mysqli_query($link, $query)) {
        /* Выборка результатов запроса */
        echo '<table border="1"> <tr id="title"><th>id</th><th>Имя</th><th>Возраст</th><th>E-mail</th><th></th></tr>';
        while( $row = mysqli_fetch_assoc($result) ){
          echo "<tr><td>".$row['id']."</td><td>".$row['name']."</td><td>".$row['age']."</td><td>".$row['email']."</td>
          <td>

          <a href='/core/delete.php?id=".$row['id']."'><img src='img/del.png' alt='Удалить' width='20px' height='20px'></a>
          <a href='/core/edit.php?id=".$row['id']."'><img src='img/edit.png' alt='Удалить' width='20px' height='20px'></a>

          </td></tr> ";
        }
        echo "</table>";

        /* Освобождаем используемую память */
        mysqli_free_result($result);
      }
      //вывод списка страниц

      for ($i=0; $i < $count_page; $i++) {
        $j = $i + 1;
        $page_numbers .= "<a href='index.php?page=$i'>$j </a> ";
      }

      echo "<div class='page_numbers'>$page_numbers</div>";
      ?>
      <a href="#"></a>
    </div>
    <div class="users_form">
      <form class="default_form" action="core/add.php" method="post">
        <h3>Добавление пользователя:</h3>
        <div class="form_inner">
          <label for="name">Имя</label>
          <input type="text" name="name" >
        </div>
        <div class="form_inner">
          <label for="age">Возраст</label>
          <input type="text" name="age" ></div>
          <div class="form_inner">
            <label for="email">E-mail</label>
            <input type="text" name="email" >
          </div>
          <button type="submit" name="button_add">Добавить пользователя</button>
          <?php
          //проверяем создалась ли кука в обработчике формы и выводим сообщение
          if(isset($_COOKIE["d22"])){
            echo "<span class='add_mesage'>Пользователь добавлен</span>";
          }
          ?>
        </form>
        <form class="default_form" action="/core/find.php" method="post">
          <h3>Поиск пользователя:</h3>
          <div class="form_inner">
            <label for="name">Имя</label>
            <input type="text" name="name" ><br>
          </div>
          <button type="submit" name="button_find">Поиск пользователя</button>
        </form>
        <?php
        /* Закрываем соединение */
        mysqli_close($link);
        ?>
      </div>
    </div>
  </body>
  </html>
