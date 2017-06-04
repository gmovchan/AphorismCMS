<div class="ya-share2" data-services="vkontakte,facebook,telegram,twitter" data-counter="" 
     data-url="http://<?php echo $data['hostLink']; ?>/quote/comments?quote_id=<?php echo $data['quote']['quote_id']; ?>" 
     data-title="Бобыльцитат — сборник афоризмов великих холостяков" 
     data-size="s" 
     data-image="http://<?php echo $data['hostLink']; ?>/img/bobyl.png"
     data-description="<?php 
     // FIXME: тег <br /> надо не удалять, а заменять на перенос /n/r
     echo strip_tags($this->html($data['quote']['description'])); 
     ?>"
     ></div>