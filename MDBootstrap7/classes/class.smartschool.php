<?php
class Smartschool
{

	var $platform ;
	var $webservicesPwd;
	var $soapclient = null;

	public function __construct()
	{
		global $config_SS;
		$this->platform = $config_SS['platform'];
		$this->webservicesPwd = $config_SS['webservicesPwd'];
		$this->soapclient = new SoapClient('https://' . $this->platform . '/Webservices/V3?wsdl', ['cache_wsdl' => WSDL_CACHE_NONE]);
	}

	private function handleError($result)
	{
		// HAAL ALLE FOUTCODES EN HUN OMSCHRIJVINGEN OP
		$errorCodes = $this->soapclient->returnJsonErrorCodes();
		$errorCodes = json_decode($errorCodes);

		// OMSCHRIJVING VOOR DEZE FOUTCODE
		$errorMessage = $errorCodes->{$result};

		// FOUTMELDING
		throw new \Exception($errorMessage);
	}

	public function bericht($zender, $ontvanger, $titel, $bericht, $attachments = "", $coaccount = 0)
	{
		/**
		 * Stuurt een smartschoolbericht
		 * @param $zender Stamboeknummer van zender
		 * @param $ontvanger Stamboeknummer van ontvanger
		 * @param $titel Onderwerp van bericht
		 * @param $bericht HTML berichtinhoud
		 * @param $coaccounts nr van de coaccount - hoofdaccount = 0
		 * @return mixed True als het gelukt is, string met errorboodschap als het mislukt is.
		 */

		try {
			$result = $this->soapclient->sendMsg($this->webservicesPwd, $ontvanger, $titel, $bericht, $zender, $attachments, $coaccount);

			if ($result !== 0) {

				$this->handleError($result);
			}

			// RESULTAAT IS CORRECT
			return true;
		} catch (\Exception $e) {
			//AFHANDELING FOUTMELDINGEN
			return "ERROR : " . $e->getMessage();
		}
	}

	public function ophalenLeerlingen($klas)
	{
		/*
		ophalen leerlingen vanuit Smartschool via klas
		we sturen JSON terug met naam, voornaam, gebruikersnaam, internummer en klasnummer
		*/
		try {
			$result = $this->soapclient->getAllAccounts($this->webservicesPwd, $klas, 0);

			if (is_int($result)) {

				$this->handleError($result);
			}

			// RESULTAAT IS CORRECT

			$xml = simplexml_load_string(base64_decode($result), null, LIBXML_NOCDATA);

			$result2 = json_encode($xml);
			/*
			$resultArray = json_decode($result2);
			foreach ($resultArray['account'] as $key => $row) {
				$naam[$key]  = $row['naam'];
				$voornaam[$key] = $row['voornaam'];
			}
			array_multisort($naam, SORT_ASC, $voornaam, SORT_ASC, $resultArray['account']);
			$result3 = json_encode($resultArray);
			*/
			return $result2;
		} catch (\Exception $e) {
			//AFHANDELING FOUTMELDINGEN
			return "ERROR : " . $e->getMessage();
		}
	}

	public function ophalenPersoneelExtended($filter)
	{
		/*
		ophalen leerlingen vanuit Smartschool via klas
		we sturen JSON terug met alle gegevens
		*/
		try {
			$result = $this->soapclient->getAllAccountsExtended($this->webservicesPwd, $filter, 1);

			if (is_int($result)) {

				$this->handleError($result);
			}

			// RESULTAAT IS CORRECT

			return json_decode($result, TRUE);
		} catch (\Exception $e) {
			//AFHANDELING FOUTMELDINGEN
			return "ERROR : " . $e->getMessage();
		}
	}

	public function ophalenAfwezigheden($datum)
	{
		/*
		ophalen leerlingen vanuit Smartschool via klas
		we sturen JSON terug met alle gegevens
		*/
		try {
			$result = $this->soapclient->getAbsentsByDate($this->webservicesPwd, $datum);

			if (is_int($result)) {

				$this->handleError($result);
			}

			// RESULTAAT IS CORRECT

			return json_decode($result, TRUE);
		} catch (\Exception $e) {
			//AFHANDELING FOUTMELDINGEN
			return "ERROR : " . $e->getMessage();
		}
	}

	public function ophalenLeerlingenExtended($klas)
	{
		/*
		ophalen leerlingen vanuit Smartschool via klas
		we sturen JSON terug met alle gegevens
		*/
		try {
			$result = $this->soapclient->getAllAccountsExtended($this->webservicesPwd, $klas, 0);

			if (is_int($result)) {

				$this->handleError($result);
			}

			// RESULTAAT IS CORRECT

			$leerlingen = json_decode($result, TRUE);
			//sorteren van de namen
			foreach ($_SESSION['leerlingen'] as $key => $row) {
				$naam[$key]  = $row['naam'];
				$voornaam[$key] = $row['voornaam'];
			}
			array_multisort($naam, SORT_ASC, $voornaam, SORT_ASC, $leerlingen);
			return (json_encode($leerlingen));
		} catch (\Exception $e) {
			//AFHANDELING FOUTMELDINGEN
			return "ERROR : " . $e->getMessage();
		}
	}

	public function ophalenAlleLeerlingenExtended()
	{
		/*
		ophalen leerlingen vanuit Smartschool via klas
		we sturen JSON terug met alle gegevens
		*/
		try {
			$result = $this->soapclient->getAllAccountsExtended($this->webservicesPwd, "LLNGOAO", 1);

			if (is_int($result)) {

				$this->handleError($result);
			}

			// RESULTAAT IS CORRECT
			return (json_decode($result, TRUE));
		} catch (\Exception $e) {
			//AFHANDELING FOUTMELDINGEN
			return "ERROR : " . $e->getMessage();
		}
	}

	public function ophalenAlleKlassen()
	{

		try {
			$result = $this->soapclient->getClassListJson($this->webservicesPwd);

			if (is_int($result)) {

				$this->handleError($result);
			}

			// RESULTAAT IS CORRECT
			return (json_decode($result, TRUE));
		} catch (\Exception $e) {
			//AFHANDELING FOUTMELDINGEN
			return "ERROR : " . $e->getMessage();
		}
	}

	public function ophalenUserDetails($internnr)
	{
		/*
		ophalen van gegevens van 1 leerling volgens internnr
		return = JSON
		*/
		try {
			$result = $this->soapclient->getUserDetails($this->webservicesPwd, $internnr);
			if (is_int($result)) {
				$this->handleError($result);
			}
			// RESULTAAT IS CORRECT
			return json_decode($result,TRUE);
		} catch (\Exception $e) {
			//AFHANDELING FOUTMELDINGEN
			return "ERROR : " . $e->getMessage();
		}
	}

	public function ophalenKlassen()
	{
		/*
		ophalen van alle klassen uit Smartschool
		array teruggeven
		*/
		try {
			$result = $this->soapclient->getClassList($this->webservicesPwd);
			if (is_int($result)) {
				$this->handleError($result);
			}
			// RESULTAAT IS CORRECT
			$klassen =  unserialize($result);
			$klasarray = array();
			$teller = 0;
			foreach ($klassen as $klas) {
				if ($klas['isOfficial'] == 1 && is_numeric(substr($klas['name'], 0, 1))) {
					$klasarray[$teller]['desc'] = $klas['desc'];
					$klasarray[$teller]['code'] = $klas['code'];
					$teller++;
				}
				$klasarray[$teller]['desc'] = "Testklas";
				$klasarray[$teller]['code'] = "TESTKLAS";
			}
			return $klasarray;
		} catch (\Exception $e) {
			//AFHANDELING FOUTMELDINGEN
			return "ERROR : " . $e->getMessage();
		}
	}

	public function WachtwoordWegschrijven($internnr, $password, $accounttype)
	{
		try {
			// INITIALISEER DE SOAP CLIENT VOOR HET SPECIFIEKE PLATFORM
			$result1 = $this->soapclient->savePassword($this->webservicesPwd, $internnr, $password, $accounttype);
			if ($result1 !== 0) {
				$this->handleError($result1);
			}
			$result2 = $this->soapclient->forcePasswordReset($this->webservicesPwd, $internnr, $accounttype);
			if ($result2 !== 0) {
				$this->handleError($result2);
			}
			// RESULTAAT IS CORRECT:
			return true;
		} catch (\Exception $e) {
			//AFHANDELING FOUTMELDINGEN
			header("HTTP/1.0 400 Error");
			return "ERROR : " . $e->getMessage();
		}
	}

	public function wegschrijvenParameter($internnr, $param, $inhoud)
	{
		try {
			// INITIALISEER DE SOAP CLIENT VOOR HET SPECIFIEKE PLATFORM
			$result = $this->soapclient->saveUserParameter($this->webservicesPwd, $internnr, $param, $inhoud);
			// INDIEN RESULTAAT NIET CORRECT
			if ($result !== 0) {
				$this->handleError($result);
			}
			// RESULTAAT IS CORRECT:
			return true;
		} catch (\Exception $e) {
			//AFHANDELING FOUTMELDINGEN
			header("HTTP/1.0 400 Error");
			return "ERROR : " . $e->getMessage();
		}
	}

	public function ophalenLeerkrachtenExtended()
	{
		/*
		ophalen alle leerkrachten extended
		we sturen JSON terug met alle gegevens
		*/
		try {
			$result = $this->soapclient->getAllAccountsExtended($this->webservicesPwd, "ALLELK", 1);

			if (is_int($result)) {
				$this->handleError($result);
			}

			// RESULTAAT IS CORRECT
			return $result;
		} catch (\Exception $e) {
			//AFHANDELING FOUTMELDINGEN
			return "ERROR : " . $e->getMessage();
		}
	}

	public function ophalenLeerkrachten()
	{
		/*
		ophalen alle leerkrachten extended
		we sturen JSON terug met alle gegevens
		*/
		try {
			$result = $this->soapclient->getAllAccounts($this->webservicesPwd, "ALLELK", 1);

			if (is_int($result)) {
				$this->handleError($result);
			}
			$xml = simplexml_load_string(base64_decode($result), null, LIBXML_NOCDATA);

			$result2 = json_encode($xml);

			// RESULTAAT IS CORRECT
			return $result2;
		} catch (\Exception $e) {
			//AFHANDELING FOUTMELDINGEN
			return "ERROR : " . $e->getMessage();
		}
	}

	public function ophalenKlastitularissen()
	{
		/* ophalen van alle klastitularissen
		return = JSON */
		try {
			$result = $this->soapclient->getClassTeachers($this->webservicesPwd, true);

			if (is_int($result)) {
				$this->handleError($result);
			}

			// RESULTAAT IS CORRECT
			return $result;
		} catch (\Exception $e) {
			//AFHANDELING FOUTMELDINGEN
			return "ERROR : " . $e->getMessage();
		}
	}

	public function ophalenfoto($internnr)
	{
		/*
		ophalen foto op internnr
		return = base64 encode image
		*/
		$result = $this->soapclient->getAccountPhoto($this->webservicesPwd, $internnr);
		if ($result == 23) {
			return false;
		} else {
			return $result;
		}
	}

	public function ophalenAlleGroepenEnKlassen()
	{
		/*
		ophalen leerlingen vanuit Smartschool via klas
		we sturen JSON terug met alle gegevens
		*/
		try {
			$result = $this->soapclient->getAllGroupsAndClasses($this->webservicesPwd);

			if (is_int($result)) {

				$this->handleError($result);
			}

			// RESULTAAT IS CORRECT
			$xml = simplexml_load_string(base64_decode($result), null, LIBXML_NOCDATA);
			return json_decode(json_encode($xml), TRUE);
		} catch (\Exception $e) {
			//AFHANDELING FOUTMELDINGEN
			return "ERROR : " . $e->getMessage();
		}
	}

	public function ophalenSkoreLK()
	{
		try {
			$result = $this->soapclient->getSkoreClassTeacherCourseRelation($this->webservicesPwd);

			if (is_int($result)) {

				$this->handleError($result);
			}

			// RESULTAAT IS CORRECT
			$xml = simplexml_load_string($result, null, LIBXML_NOCDATA);
			return json_decode(json_encode($xml), TRUE);
		} catch (\Exception $e) {
			//AFHANDELING FOUTMELDINGEN
			return "ERROR : " . $e->getMessage();
		}
	}

	public function deblokkeer($internnr)
	{
		/*
		deblokkeren van een leerling
		*/
		try {
			$result = $this->soapclient->setAccountStatus($this->webservicesPwd, $internnr, 'actief');

			if (is_int($result)) {

				$this->handleError($result);
			}
			return true;
		} catch (\Exception $e) {
			//AFHANDELING FOUTMELDINGEN
			return "ERROR : " . $e->getMessage();
		}
	}

	public function bewaarleerkracht($params)
	{
		/*
		bewaar een nieuwe LK
		*/
		try {
			$result = $this->soapclient->saveUser($this->webservicesPwd, $params['internumber'], $params['username'], $params['passwd1'], "", "", $params['name'], $params['surname'], "", "", $params['sex'], $params['birthday'], $params['birthplace'], $params['birthcountry'], $params['address'], $params['postalcode'], $params['location'], $params['country'], $params['email'], $params['mobilephone'], $params['homephone'], "", $params['prn'], $params['stamboeknummer'], $params['basisrol'], "");

			// RESULTAAT IS CORRECT
			return $result;
		} catch (\Exception $e) {
			//AFHANDELING FOUTMELDINGEN
			return "ERROR : " . $e->getMessage();
		}
	}

	public function OfficieleKlas($internnr)
	{
		/*
		ophalen officiele klas van een leerling
		*/
		try {
			$result = $this->soapclient->getUserOfficialClass($this->webservicesPwd, $internnr);

			if (is_int($result)) {

				$this->handleError($result);
			}

			// RESULTAAT IS CORRECT
			return json_decode($result, TRUE);
		} catch (\Exception $e) {
			//AFHANDELING FOUTMELDINGEN
			return "ERROR : " . $e->getMessage();
		}
	}

	public function SkoreSync()
	{
		/*
		starten van de sync met Skore
		*/
		try {
			$result = $this->soapclient->startSkoreSync($this->webservicesPwd);

			if (is_int($result)) {

				$this->handleError($result);
			}

			// RESULTAAT IS CORRECT
			return json_decode($result, TRUE);
		} catch (\Exception $e) {
			//AFHANDELING FOUTMELDINGEN
			return "ERROR : " . $e->getMessage();
		}
	}
}
