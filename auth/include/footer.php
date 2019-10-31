	<!-- Bootstrap core JavaScript-->
	<script src="../assets/vendor/jquery/jquery.min.js"></script>
	<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

	<!-- Core plugin JavaScript-->
	<script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>

	<!-- Custom scripts for all pages-->
	<script src="../assets/js/sb-admin-2.min.js"></script>

	<script>
		function validateEmail(email) {
				var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
				return emailReg.test(email);
		}

		$(document).ready(function() {
			$( "#createAccountBtn" ).click(function(event){
				var isCorrect = 1;
				var message = '';
				var firstName = $.trim($('#firstName').val());
				var lastName = $.trim($('#lastName').val());
				var email = $.trim($('#email').val());
				var password = $.trim($('#password').val());
				var repeatPassword = $.trim($('#repeatPassword').val());
				var cnic = $.trim($('#cnic').val());
				var phone = $.trim($('#phone').val());
				var passwordLength = $("#password").val().length;
				var cnicLength = $("#cnic").val().length;
				var phoneLength = $("#phone").val().length;

				// Check if first name is empty or not
				if (firstName  === '') {
					isCorrect = 0;
					message = 'First Name is empty.';
				} else if (lastName  === '') {
					isCorrect = 0;
					message = 'Last Name is empty.';
				} else if (email  === '') {
					isCorrect = 0;
					message = 'Email is empty.';
				} else if (password  === '') {
					isCorrect = 0;
					message = 'Password is empty.';
				} else if (repeatPassword  === '') {
					isCorrect = 0;
					message = 'Repeat Password field is empty.';
				} else if (cnic  === '') {
					isCorrect = 0;
					message = 'CNIC is empty.';
				} else if (phone  === '') {
					isCorrect = 0;
					message = 'Phone Number is empty.';
				} else if(!validateEmail(email)) {
					isCorrect = 0;
					message = 'Email validaiton failed.';
				} else if(passwordLength<8){
					isCorrect = 0;
					message = 'Password must be 8 characters long.';
				}	else if (password  !== repeatPassword) {
					isCorrect = 0;
					message = 'Password fields are not match.';
				} else if(!$.isNumeric(cnic)){
					isCorrect = 0;
					message = 'CNIC must be a numeric.';
				} else if(cnicLength!==13){
					isCorrect = 0;
					message = 'CNIC length must be equal to 13 digits.';
				} else if(!$.isNumeric(phone)){
					isCorrect = 0;
					message = 'Phone Number must be a numeric.';
				} else if (phoneLength  !== 11) {
					isCorrect = 0;
					alert('Phone Number length must be equal to 11 digits.');
					return false;
				}
				if (isCorrect) {
					$("#regForm").submit();
				} else {
					alert(message);
					event.preventDefault();
				}
			});
		});
	</script>

</body>

</html>
