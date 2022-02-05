<?php
	$controller =  $this->request->getParam('controller');
	$action = $this->request->getParam('action');
	$pass = $this->request->getParam('pass');
	$pass = (isset($pass[0])) ? $pass[0] : '';

	/* if( $_SERVER['REMOTE_ADDR'] == '183.83.42.60' ){
		echo "controller- $controller<br/>action- $action";
		pr($pass);
	} */

	if($controller == 'Home' && $action == 'index'){

		echo '<title>SportsIndia11 | Play Online Fantasy Sports Games and Win Prizes</title>';
		echo '<meta name="description" content="SportsIndia11 is the most authentic & thrilling online gaming platform in India. Play Cricket, Football, Kabaddi, Hockey, Poker, Rummy, and Quiz and win cash prizes!" />';

		/* echo '
		<script type="application/ld+json">
			{
			"@context": "https://schema.org",
			"@type": "FAQPage",
			"mainEntityOfPage": [{
				"@type": "Question",
				"name": "Is SportsIndia11 a fair platform?",
				"acceptedAnswer": {
				"@type": "Answer",
				"text": "Of course, SportsIndia11 is an online fantasy sports gaming platform that strictly follows fantasy sports and gaming laws and conducts leagues and tournaments by sticking to fair game policy."
				}
			},{
				"@type": "Question",
				"name": "Is SportsIndia11 a secured platform?",
				"acceptedAnswer": {
				"@type": "Answer",
				"text": "Yes, of course, SportsIndia11 is a highly secured online gaming platform. For us, security is the imperative parameter and we are committed to safeguarding user’s privacy."
				}
			},{
				"@type": "Question",
				"name": "Which online games can I play?",
				"acceptedAnswer": {
				"@type": "Answer",
				"text": "We offer various high-performing and user-interactive online games for our sports and game lovers to provide the best gaming experience. Our online gaming platform has fantasy cricket, fantasy football, fantasy hockey, fantasy kabaddi, online Rummy, online Poker, and Quiz."
				}
			},{
				"@type": "Question",
				"name": "What are the payment modes for the transaction?",
				"acceptedAnswer": {
				"@type": "Answer",
				"text": "We are a customer-centric fantasy gaming platform. SportsIndia11 has a plethora of online payment options for our customers. You can pick your payment mode as per your convenience for transactions. Our payment modes have all major kinds of credit/debit cards including Visa, MasterCard, Maestro, etc."
				}
			},{
				"@type": "Question",
				"name": "How will I be notified in case I win a Cash prize?",
				"acceptedAnswer": {
				"@type": "Answer",
				"text": "SportsIndia11 notifies the final ranks and scores just at the end of a match. In an event a gamer wins a cash prize after participating in a contest(s), the winnings amount will be credited to the SportsIndia11 account of the winner."
				}
			}]
			}
		</script>
		'; */

	} else if ($action =='about' || $pass=='about-us'){	
		
		echo '<title>About Us- SportsIndia11</title>';
		echo '<meta name="description" content="We are a trusted online gaming platform that offers various online fantasy sports and games such as Fantasy Cricket, Rummy, Quiz, & lots more for sports & games fans." />';
		
	} else if ($action =='Privacy' || $pass=='privacy-policy'){		
		
		echo '<title>Privacy Policy- SportsIndia11</title>';
		echo '<meta name="description" content="SportsIndia11, the most trusted platform that is committed to safeguarding your personal details that are used by our services." />';
		
	} else if ($action =='Contact'){		
		
		echo '<title>Get In Touch With Us- SportsIndia11</title>';
		echo '<meta name="description" content="Contact our SportsIndia11 support team for any glitches. Our team is available to address your queries and find a solution for offering a seamless gaming experience." />';
		
	}  else if ($action =='Terms & Conditions' || $pass=='terms-and-condition'){		
		
		echo '<title>Terms And Conditions- SportsIndia11</title>';
		echo '<meta name="description" content="By using SportsIndia11, you automatically agree with the terms of use as mentioned on our website." />';
		
	} else if ( ( $controller == 'Home' && $action == 'Howtoplay' ) || $pass=='how-to-play'){
		
		echo '<title>How To Play- SportsIndia11</title>';
		echo '<meta name="description" content="Go through the detailed How-to guide and learn how to play online fantasy sports & games and win cash prizes." />';
	}

?>