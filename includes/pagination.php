        <div class="mathematics__pagination">
            <a class="link link__pagination <?=($current_page) === 1 ? 'link__arrow': null;?>" href="index.php?page=<?=$_GET['page']?>&pagination=<?=($current_page - 1) < 1 ? 1 : $current_page - 1;?>"><img src="./assets/icons/left_arrow.svg" alt="fleche gauche"></a>
            <?php for ($i = 1; $i <= $pages; $i++) { ?>
            <a class="link link__pagination <?=($current_page) === $i ? 'link__number' : null;?>" href="index.php?page=<?=$_GET['page']?>&pagination=<?=$i;?>"><?=$i?></a>
            <?php } ?>
            <a class="link link__pagination <?=($current_page) >= $pages ? 'link__arrow': null;?>" href="index.php?page=<?=$_GET['page']?>&pagination=<?=($current_page + 1) > $pages ? $pages : $current_page + 1;?>"><img src="./assets/icons/right_arrow.svg" alt="fleche gauche"></a>
        </div>
    </div>
</div>

