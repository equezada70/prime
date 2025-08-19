<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// [SQL] Read & Work with Tables
	function connectDB() {
	    global $dbHost, $dbUser, $dbPass, $dbName;
	    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
	    if ($conn->connect_error) {
	        die("Connection failed: " . $conn->connect_error);
	    } else {
			// Optional: Set character set
			if (!mysqli_set_charset($conn, "utf8mb4")) {
				// error_log("Error loading character set utf8mb4: " . mysqli_error($conn));
				// Decide if this is a fatal error for your application
			}
		}
	    return $conn;
	}

	function readData($table,$field, $value, $return, $default ='No Value') {
		$conn = connectDB();
		$sql = "SELECT * from `".$table."` WHERE `".$table."`.`".$field."` = '".$value."';";
		$result_pick = $conn->query($sql);
		if($result_pick->num_rows === 0) {
//			$result = '4-Error lookin for the data using the SQL: '.$sql.'.  Error: '. $conn->error . '<br/>Solve the problem: <button style="background-color: red; color: white; border: none; padding: 10px 20px; cursor: pointer;" onclick="window.location.href=\''.$root_path.'/new_system/secured/modules/setup/system_integrity/tableIntegrity/reset_system_data.php\'"> Solve the Problem </button>';
			$result = $default;

			$debug_mode = 'yes';
			if ($debug_mode=='yes') {
				echo 'ERROR<hr/>';
				echo 'Table : ' . $table . '<br/>';
				echo 'Field: '. $field . '<br/>';
				echo 'Value: ' . $value . '<hr/>';
				echo 'Return: ' . $return . '<hr/>';
				echo 'SQL: ' . $sql . '<hr/>';
				die($return);
			}
		}else{
			$row = $result_pick->fetch_assoc();
			$result = $row[$return];
		}
		return $result;
	}



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// [COOKIE] Work with Cookies
	function checkCookieLanguage() {
		$cookieLanguage = 'cookieLanguage';
		if (isset($_COOKIE[$cookieLanguage]) && $_COOKIE[$cookieLanguage] !== '') {$languageValue = $_COOKIE[$cookieLanguage];}else{$languageValue = detectBrowserLanguage();setcookie($cookieLanguage, $languageValue, 90);}
		return $languageValue;
	}

	function existCookie($cookieName){
		$returnValue = false;
		if (isset($_COOKIE[$cookieName]) && $_COOKIE[$cookieName] !== '') {
			$returnValue = $_COOKIE[$cookieName];
		}
		return $returnValue;
	}

	function getCookieValue($cookie_name, $default) {
		// Check if cookie exists and is not empty
		if (isset($_COOKIE[$cookie_name]) && $_COOKIE[$cookie_name] !== '') {
			return htmlspecialchars($_COOKIE[$cookie_name]);
		}
		return $default;
	}

	function setCookieValue($cookieName, $cookieValue, $numberHours){
		setcookie($cookieName, $cookieValue, time()+(3600*$numberHours), '/');
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// [BROWSER] Work with browser values
	function detectBrowserLanguage($full = false, $default = 'en') {
		if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {return $default;}
		$accept_language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
		$languages = [];
		// Split into individual language entries
		$parts = array_map('trim', explode(',', $accept_language));
		$index = 0;
		foreach ($parts as $part) {
			// Split language tag and parameters
			$split = explode(';', $part);
			$tag = trim(array_shift($split));
			// Skip wildcard entries
			if ($tag === '*') {continue;}
			$q = 1.0;
			// Process parameters to find q-factor
			foreach ($split as $param) {
				$param = trim($param);
				if (strpos($param, 'q=') === 0) {
					$q = (float) substr($param, 2);
					break;
				}
			}
			$languages[] = [
			'tag'   => $tag,
			'q'     => $q,
			'index' => $index++
			];
		}
		if (empty($languages)) {return $default;}
		// Sort languages by q-value (descending) and original order (ascending)
		usort($languages, function($a, $b) {
			if ($a['q'] === $b['q']) {return $a['index'] - $b['index'];}
			return $a['q'] > $b['q'] ? -1 : 1;
		});
		
		$preferred = $languages[0]['tag'];
		if ($full) {
			return $preferred; // Return full language tag (e.g., en-US)
		}
		// Extract primary language subtag (e.g., en from en-US)
		$primary = explode('-', $preferred)[0];
		return strtolower($primary);
	}

?>