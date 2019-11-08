<?php

echo <<<HERE
<form action='./registration.php?registration' method='POST'>
    <div class='user_data'>
        <p> {$win_data['name']}
HERE;
        if($win_data['phone']) echo ", {$win_data['phone']}";
echo <<<HERE
        </p><p>
HERE;
        if($win_data['index']){
            echo "{$win_data['index']}, ";
}
     echo <<<HERE
        {$win_data['country']}, {$win_data['city']}, {$win_data['address']}
HERE;

if ($win_data['a_info']) {
    echo " ({$win_data['a_info']})</p>";
} else {
    echo "</p>";
}

if($win_data['p_info']) {
    echo "<p>подробности: {$win_data['p_info']}</p>";
}
$name = explode(' ', $win_data['name']);
$city = substr(strstr($win_data['city'], ', '), 2);
$state = strstr($win_data['city'], ', ',  true);
$id = implode(',', get_id_comp($pdo));

echo <<<HERE
    <input id="id_winner" name="id_winner" value="{$id}" type="hidden"/>
    <input id="firstname" name="firstname" value="{$name[1]}" type="hidden"/>
    <input id="secondname" name="secondname" value="{$name[2]}" type="hidden"/>
    <input id="lastname" name="lastname" value="{$name[0]}" type="hidden"/>
    <input id="phone" name="phone" value="{$win_data['phone']}" type="hidden"/>
    <input id="index" name="index" value="{$win_data['index']}" type="hidden"/>
    <input id="country" name="country" value="{$win_data['country']}" type="hidden"/>
    <input id="state" name="state" value="{$state}" type="hidden"/>
    <input id="city" name="city" value="{$city}" type="hidden"/>
    <input id="address" name="address" value="{$win_data['address']}" type="hidden"/>
    <input id="address_info" name="address_info" value="{$win_data['a_info']}" type="hidden"/>
    <input id="other_info" name="other_info" value="{$win_data['p_info']}" type='hidden'/>
    <input id="paper" name="paper" checked='checked' value="true" type="hidden"/>

    <button class='select_address_btn' type='submit'>Выбрать этот адрес для доставки</button></div>
</form>
HERE;

?>