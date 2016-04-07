<ul class="loginbar">
    <li>
        <?php
        if (Yii::app()->user->isGuest) {
            $login_link = CHtml::link('Login', Yii::app()->user->loginUrl, array('class' => 'loginlink'));
            if ($this->action->id != 'login')
                echo $login_link;
        }
        else {
            echo '<span style="margin-left:20px;">' . CHtml::link('Change Password', Yii::app()->baseUrl.'/user/updatePassword/'.Yii::app()->user->id, array('class' => 'loginlink')) . '</span>';
            echo '<span style="margin-left:20px;">' . CHtml::link('Logout', Yii::app()->user->logoutUrl, array('class' => 'loginlink')) . '</span>';
        }
        ?>
    </li>
</ul>
