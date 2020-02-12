<!DOCTYPE html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/css/mobile.css" />
    <link rel="stylesheet" href="/css/stylesheet.css" />
  </head>

  <body>
    <form action='post.php' method='POST'>
      <div class='play-box'>
        <div class='play-content'>
          <input type='text' name='pin' maxlength='8' size='12' placeholder='Pin'>
        </div>
        <div class='play-content'>
          <input type='text' name='username' maxlength='15' minlength='2' size='18' placeholder='Username'>
        </div>
        <button class='play-submit' type='submit'>Play</button>
      </div>
    </form>
  </body>
</html>