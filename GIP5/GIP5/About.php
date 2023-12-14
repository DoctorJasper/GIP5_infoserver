<!DOCTYPE html>
<?php
// Inclusie van startphp.php en header.php voor hergebruik van code
require("startphp.php");
require("header.php"); 
?>

<!-- Hoofdgedeelte van de pagina met een container -->
<div class="container mt-5">
  <!-- Een rij gecentreerd op de pagina -->
  <div class="row justify-content-center">
    <!-- Een kolom die de volledige breedte inneemt op kleine schermen -->
    <div class="col-sm-12">
      <!-- Een kaart (card) om informatie te structureren -->
      <div class="card">
        <!-- Kop van de kaart -->
        <div class="card-header">
          Over ons
        </div>
        <!-- Lichaam van de kaart -->
        <div class="card-body">
          <!-- Een formulier met actie "process_account.php" en verzendmethode "post" -->
          <form action="process_account.php" method="post">
            <!-- Een groep voor een formulieronderdeel -->
            <div class="form-group">
              <h2>Over ons</h2>
              <!-- Paragraaf met informatie over de makers en het project -->
              <p>Wij zijn Jasper T'Kindt en Kyan Vanderkelen, studenten in de richting van 6 informaticabeheer. ... (tekst gaat verder) ... lessen en uitleg zullen ontvangen met betrekking tot het koppelen van databases met code, zijn we vastberaden om aan de verwachtingen te voldoen.</p>

              <br>

              <h2>Onderzoeksvraag</h2>
              <p>De kern van ons project is gericht op het verbeteren van tijdsbeheer en het vereenvoudigen van taken voor administrators ... (tekst gaat verder) ... gemakkelijke toegang tot het systeem mogelijk voor alle gebruikers. Belangrijke functionaliteiten omvatten accountcreatie, -wijziging, -beheer, toegankelijke tutorials en foutdetectie via logbestanden.</p>

              <br>

              <h2>Ontwikkelingsproces</h2>
              <p>Ons project wordt ontwikkeld met behulp van CSS, HTML, Linux, JavaScript, PHP en MD Bootstrap 5. PHP zal fungeren als de brug ... (tekst gaat verder) ... Beheerders hebben de mogelijkheid om gegevens aan te passen, updaten of verwijderen. Leerlingen kunnen hun wachtwoord wijzigen en hun gegevens updaten.</p>

              <br>

              <h2>Opdrachtgever</h2>
              <p>Onze opdrachtgever is Bart Kindt, leerkracht wiskunde en informatica in GO! atheneum Oudenaarde, te bereiken via Bart.Kindt@go-ao.be.</p>

              <br>

              <h2>Functionele Eisen</h2>
              <p>Het project omvat functionaliteiten zoals inlogmogelijkheden, accountbeheer, klasbeheer, tutorials en een directe link naar de persoonlijke homepage van de gebruikers.</p>

              <br>

              <h2>Conclusie</h2>
              <p>Het "Info Server" project beoogt een geïntegreerd platform te zijn dat administratieve taken rondom account- en klasbeheer vereenvoudigt. ... (tekst gaat verder) ... positieve impact hebben op de school en een verbetering betekenen voor beheerders en gebruikers.</p>

              <br>

              <!-- Een link naar "adminpage.php" met tekst "Terug" -->
              <a class="nav-link" href="adminpage.php">Terug</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
