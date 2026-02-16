
        <?php
            if($this->session->flashdata('msg')!==NULL || $this->session->flashdata('err_msg')!==NULL){
                $msg=$this->session->flashdata('msg');
                $err_msg=$this->session->flashdata('err_msg');
        ?>
        <div class="notify toastr-notify d-none" data-from="top" data-align="right" data-status="<?= !empty($msg)?'success':'error'; ?>" data-title="<?= !empty($msg)?'Success':'Error'; ?>"><?= !empty($msg)?$msg:$err_msg; ?></div>
        <?php
            }
        ?>
        <div id="body-overlay" style="display: none;"></div>
        </div>
       <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        <script src="<?= file_url('includes2/js/scripts.js'); ?>"></script>
        <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
        <script src="https://threejs.org/examples/js/libs/stats.min.js"></script>
<script>
  particlesJS("particles-js", {
    particles: {
      number: {
        value: 60,
        density: {
          enable: true,
          value_area: 800
        }
      },
      color: {
        value: ["#00ffff", "#ff00ff", "#ffffff"]
      },
      shape: {
        type: "circle"
      },
      opacity: {
        value: 0.6,
        random: true
      },
      size: {
        value: 4,
        random: true
      },
      move: {
        enable: true,
        speed: 1.8,
        direction: "none",
        random: true,
        straight: false,
        out_mode: "out"
      },
      line_linked: {
        enable: false
      }
    },
    interactivity: {
      events: {
        onhover: {
          enable: true,
          mode: "grab"
        },
        onclick: {
          enable: true,
          mode: "push"
        }
      },
      modes: {
        grab: {
          distance: 180,
          line_linked: {
            opacity: 0.3
          }
        },
        push: {
          particles_nb: 3
        }
      }
    },
    retina_detect: true
  });
</script>
      <script>
         const sidebar = document.getElementById('sidebar');
         const toggleBtn = document.getElementById('mobile-menu-toggle');
         
         toggleBtn.addEventListener('click', () => {
             sidebar.classList.toggle('active'); // add/remove active class
         });
      </script>
        <script src="<?= file_url('includes2/custom/custom.js'); ?>"></script>
   </body>
</html>
