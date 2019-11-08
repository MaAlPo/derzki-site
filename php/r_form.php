<?php

echo <<<HERE
    <div class="forma">
        <form action="registration.php?registration" method="POST">
            <h3>Введите данные получателя:</h3>
            <input id="id_winner" name="id_winner" value="{$winner_data['id']}" type="hidden"/>
            <input id="firstname" name="firstname" placeholder="Имя" type="text"/>
            <!--<img src="content/error16.png" alt="error"/><br>-->
            <input id="secondname" name="secondname" placeholder="Отчество" type="text"/><br>
            <input id="lastname" name="lastname" placeholder="Фамилия" type="text"/><br>
            <input id="phone" name="phone" placeholder="Номер телефона" type="tel"/><br>
            <input id="index" name="index" placeholder="Почтовый индекс" type="text"/><br>
            <input id="country" name="country" placeholder="Страна" type="text"/><br>
            <input id="state" name="state" placeholder="Регион" type="text"/><br>
            <input id="city" name="city" placeholder="Город" type="text"/><br>
            <input id="address" name="address" placeholder="Адрес" type="text"/><br>
            <input id="address_info" name="address_info" placeholder="Дополнения к адресу (частный дом, а/я)" type="text"/><br>
            <textarea id="other_info" name="other_info" placeholder="Здесь Вы можете указать пожелания к призу (цвет, размер, номер)"></textarea><br>
            <input id="paper" name="paper" value="true" type="checkbox"/><span>Я принимаю условия <a href='#'>Пользовательского соглашения</a></span><br>
            <button type="submit">Отправить данные</button>
        </form>
    </div>
HERE;

?>