<?php
$nickname = $_POST['nickname'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$gender = $_POST['gender'] ?? '';
$genre = $_POST['genre'] ?? '';
$memo = $_POST['memo'] ?? '';

function clean($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="ko">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>가입 결과 - Storical</title>
    <link rel="stylesheet" href="./style.css" />
  </head>
  <body>
    <header class="site-header">
      <a class="brand" href="./index.html"><span>S</span>Storical</a>
      <nav class="main-nav">
        <a href="./index.html">홈</a>
        <a href="./join.html">회원가입</a>
      </nav>
    </header>

    <main>
      <section class="panel result-panel">
        <p class="kicker">Result</p>
        <h1>회원가입 결과</h1>
        <p>아래 정보가 PHP로 전달되었습니다.</p>

        <table>
          <tr>
            <th>닉네임</th>
            <td><?php echo clean($nickname); ?></td>
          </tr>
          <tr>
            <th>이메일</th>
            <td><?php echo clean($email); ?></td>
          </tr>
          <tr>
            <th>비밀번호</th>
            <td><?php echo $password === '' ? '입력 안 됨' : '입력됨'; ?></td>
          </tr>
          <tr>
            <th>성별</th>
            <td><?php echo clean($gender); ?></td>
          </tr>
          <tr>
            <th>좋아하는 장르</th>
            <td><?php echo clean($genre); ?></td>
          </tr>
          <tr>
            <th>가입 이유</th>
            <td><?php echo nl2br(clean($memo)); ?></td>
          </tr>
        </table>

        <p class="notice">데이터베이스에 저장하지 않고, 입력값을 화면에 보여주는 단계입니다.</p>

        <a class="small-button" href="./join.html">다시 입력하기</a>
        <a class="small-button gray" href="./index.html">홈으로</a>
      </section>
    </main>

    <footer>
      <p>PHP 기초 연습 페이지</p>
    </footer>
  </body>
</html>
