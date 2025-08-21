<header<?php style="position:<?php fixed;<?php top:<?php 0;<?php z-index:<?php 3;<?php width:<?php 100%;<?php background-color:<?php #0a2332cc;<?php height:<?php 75px;<?php border-bottom:<?php 1px<?php solid<?php #34d39956;<?php overflow:<?php visible;"><?php 
<?php 
<?php 
<?php <div<?php style="width:<?php 95%;<?php height:<?php 100%;<?php max-width:<?php 1200px;<?php display:<?php flex;<?php justify-content:<?php space-between;<?php align-items:<?php center;<?php margin:<?php 0<?php auto;"><?php 
<?php <div<?php onclick="openProfile(event)"<?php class="user_container"<?php style="display:flex;<?php justify-content:<?php space-between;<?php align-items:<?php center;<?php color:<?php #34D399;<?php gap:<?php 0.6rem;<?php font-size:<?php 17px;<?php padding:<?php 12px<?php 12px;<?php border-radius:<?php 8px;<?php cursor:<?php pointer;"><?php 
<?php <i<?php class="fa-solid<?php fa-user"></i><?php 
<?php <p<?php style="font-weight:600;"><?php=<?php $nome;<?php ?></p><?php 
<?php <i<?php class="fa<?php fa-chevron-right"<?php style="transform:<?php rotate(90deg);<?php font-size:<?php 14px;<?php "></i><?php 
<?php <div<?php class="menu-profile"<?php style="position:<?php absolute;<?php top:64px;<?php z-index:4;<?php transform:<?php translateX(-12px);<?php display:none;<?php flex-direction:<?php column;<?php justify-content:<?php space-around;<?php background-color:<?php #0a2332;<?php font-size:<?php 17px;<?php padding:<?php 8px<?php 0px;<?php border-radius:<?php 8px;<?php cursor:<?php pointer;<?php min-width:<?php 180px;<?php border:<?php 1px<?php solid<?php #34d39956;"><?php 
<?php <p<?php onclick="openEditModal()"<?php class="edit-profile"><i<?php class="fa-solid<?php fa-pen"<?php style="margin-right:<?php 12px;<?php font-size:<?php 15px;<?php vertical-align:<?php middle;<?php padding:<?php 12px;"></i>Editar<?php Perfil</p><?php 
<?php <p<?php onclick="window.location.href='/logout';"<?php class="button-logout"<?php style="color:<?php #F87171"><?php <i<?php class="fa-solid<?php fa-arrow-right-from-bracket"<?php style="margin-right:<?php 12px;<?php font-size:<?php 15px;<?php vertical-align:<?php middle;padding:<?php 12px;"></i>Sair</p><?php 
<?php </div><?php 
<?php 
<?php <style><?php 
<?php .user_container:hover<?php {<?php 
<?php background-color:<?php rgba(16,<?php 185,<?php 129,<?php .1);<?php 
<?php }<?php 
<?php 
<?php .edit-profile:hover<?php {<?php 
<?php background-color:<?php rgba(16,<?php 185,<?php 129,<?php .1);<?php 
<?php }<?php 
<?php 
<?php .button-logout:hover<?php {<?php 
<?php background-color:<?php rgba(255,<?php 0,<?php 0,<?php .1);<?php 
<?php }<?php 
<?php </style><?php 
<?php <script><?php 
<?php const<?php openProfile<?php =<?php (event)<?php =><?php {<?php 
<?php const<?php menuProfile<?php =<?php document.querySelector('.menu-profile');<?php 
<?php 
<?php if<?php (menuProfile.style.display<?php ===<?php 'flex')<?php {<?php 
<?php menuProfile.style.display<?php =<?php 'none';<?php 
<?php }<?php else<?php {<?php 
<?php menuProfile.style.display<?php =<?php 'flex';<?php 
<?php }<?php 
<?php 
<?php event.stopPropagation();<?php 
<?php };<?php 
<?php 
<?php document.addEventListener('click',<?php (event)<?php =><?php {<?php 
<?php const<?php menuProfile<?php =<?php document.querySelector('.menu-profile');<?php 
<?php const<?php userContainer<?php =<?php document.querySelector('.user_container');<?php 
<?php 
<?php if<?php (!menuProfile.contains(event.target)<?php &&<?php !userContainer.contains(event.target))<?php {<?php 
<?php menuProfile.style.display<?php =<?php 'none';<?php 
<?php }<?php 
<?php });<?php 
<?php </script><?php 
<?php </div><?php 
<?php 
<?php <div<?php style="width:<?php 40%;<?php max-width:<?php 200px;<?php display:<?php flex;<?php align-items:<?php center;<?php justify-content:<?php space-between;"><?php 
<?php 
<?php <div<?php style="color:<?php #34D399;"><?php 
<?php R$<?php <?php=<?php number_format($saldo,<?php 2,<?php ',',<?php '');<?php ?><?php 
<?php </div><?php 
<?php 
<?php <div<?php style="color:<?php #34D399;"><?php 
<?php <i<?php onclick="getNotification()"<?php class="fa-solid<?php fa-bell"<?php style="font-size:<?php 20px;<?php cursor:pointer;"></i><?php 
<?php </div><?php 
<?php 
<?php <script><?php 
<?php const<?php getNotification<?php =<?php ()<?php =><?php {<?php 
<?php Notiflix.Loading.standard();<?php 
<?php Notiflix.Loading.remove(1000);<?php 
<?php setTimeout(()<?php =><?php {<?php 
<?php Notiflix.Notify.success('Nenhuma<?php notificação!')<?php 
<?php },<?php 1500)<?php 
<?php }<?php 
<?php </script><?php 
<?php 
<?php <div<?php id="volumeIcon"<?php style="color:<?php #34D399;"><?php 
<?php <i<?php class="fa-solid<?php fa-volume-high"<?php style="font-size:<?php 18px;<?php cursor:pointer;"></i><?php 
<?php </div><?php 
<?php 
<?php <audio<?php id="bgMusic"<?php loop><?php 
<?php <source<?php src="/assets/music.mp3"<?php type="audio/mpeg"><?php 
<?php </audio><?php 
<?php 
<?php <script><?php 
<?php let<?php audio<?php =<?php document.getElementById('bgMusic');<?php 
<?php let<?php volumeIcon<?php =<?php document.getElementById('volumeIcon').querySelector('i');<?php 
<?php let<?php isPlaying<?php =<?php false;<?php 
<?php audio.volume<?php =<?php 0.3;<?php 
<?php 
<?php const<?php startMusicOnFirstClick<?php =<?php ()<?php =><?php {<?php 
<?php if<?php (!isPlaying)<?php {<?php 
<?php audio.play().catch(error<?php =><?php console.log("Erro<?php ao<?php iniciar<?php o<?php áudio:",<?php error));<?php 
<?php isPlaying<?php =<?php true;<?php 
<?php document.removeEventListener('click',<?php startMusicOnFirstClick);<?php 
<?php }<?php 
<?php };<?php 
<?php 
<?php document.addEventListener('click',<?php startMusicOnFirstClick);<?php 
<?php 
<?php volumeIcon.addEventListener('click',<?php ()<?php =><?php {<?php 
<?php if<?php (audio.paused)<?php {<?php 
<?php audio.play();<?php 
<?php volumeIcon.classList.replace('fa-volume-xmark',<?php 'fa-volume-high');<?php 
<?php Notiflix.Notify.success('Música<?php Ativada');<?php 
<?php }<?php else<?php {<?php 
<?php audio.pause();<?php 
<?php Notiflix.Notify.info('Música<?php Desativada');<?php 
<?php volumeIcon.classList.replace('fa-volume-high',<?php 'fa-volume-xmark');<?php 
<?php }<?php 
<?php });<?php 
<?php </script><?php 
<?php 
<?php 
<?php 
<?php </div><?php 
<?php 
<?php </div><?php 
</header>