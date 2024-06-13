<?php

print '
<div class="contact">
        <h2>Kontaktirajte nas</h2>
        <form action="" id="contact_form" name="contact_form" method="POST">
            <label for="first_name">Ime *</label>
            <input type="text" id="first_name" name="first_name" placeholder="Vaše ime.." required>

            <label for="last_name">Prezime *</label>
            <input type="text" id="last_name" name="last_name" placeholder="Vaše prezime.." required>

            <label for="email">Email *</label>
            <input type="email" id="email" name="email" placeholder="Vaš email.." required>
            
            <label for="subject">Predmet *</label>
            <textarea id="subject" name="subject" placeholder="Vaš predmet.." required></textarea>
            
            <input type="submit" value="Pošalji">
        </form>
        <p><a href="index.php">Povratak</a></p>
    </div>
' 
?>