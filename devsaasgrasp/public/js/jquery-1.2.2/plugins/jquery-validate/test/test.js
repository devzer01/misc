jQuery.validator.defaults.debug = true;

module("validator");

test("Constructor", function() {
	var v1 = $("#testForm1").validate();
	var v2 = $("#testForm1").validate();
	equals( v1, v2, "Calling validate() multiple times must return the same validator instance" );
	equals( 2, v1.elements().length, "validator must have two elements" );
});

test("valid() plugin method", function() {
	var form = $("#userForm");
	form.validate();
	ok ( !form.valid(), "Form isn't valid yet" );
	var input = $("#username");
	ok ( !input.valid(), "Input isn't valid either" );
	input.val("Hello world");
	ok ( form.valid(), "Form is now valid" );
	ok ( input.valid(), "Input is valid, too" );
});

test("addMethod", function() {
	expect( 3 );
	$.validator.addMethod("hi", function(value) {
		return value == "hi";
	}, "hi me too");
	var method = $.validator.methods.hi;
		e = $('#text1')[0];
	ok( !method(e.value, e), "Invalid" );
	e.value = "hi";
	ok( method(e.value, e), "Invalid" );
	ok( jQuery.validator.messages.hi == "hi me too", "Check custom message" );
});

test("addMethod2", function() {
	expect( 4 );
	$.validator.addMethod("complicatedPassword", function(value, element, param) {
		return this.optional(element) || /\D/.test(value) && /\d/.test(value)
	}, "Your password must contain at least one number and one letter");
	var v = jQuery("#form").validate({
		rules: {
			action: { complicatedPassword: true }
		}
	});
	var rule = $.validator.methods.complicatedPassword,
		e = $('#text1')[0];
	e.value = "";
	ok( v.element(e), "Rule is optional, valid" );
	equals( 0, v.size() );
	e.value = "ko";
	ok( !v.element(e), "Invalid, doesn't contain one of the required characters" );
	e.value = "ko1";
	ok( v.element(e) );
});

test("form(): simple", function() {
	expect( 2 );
	var form = $('#testForm1')[0];
	var v = $(form).validate();
	ok( !v.form(), 'Invalid form' );
	$('#firstname').val("hi");
	$('#lastname').val("hi");
	ok( v.form(), 'Valid form' );
});

test("form(): checkboxes: min/required", function() {
	expect( 3 );
	var form = $('#testForm6')[0];
	var v = $(form).validate();
	ok( !v.form(), 'Invalid form' );
	$('#form6check1').attr("checked", true);
	ok( !v.form(), 'Invalid form' );
	$('#form6check2').attr("checked", true);
	ok( v.form(), 'Valid form' );
});
test("form(): selects: min/required", function() {
	expect( 3 );
	var form = $('#testForm7')[0];
	var v = $(form).validate();
	ok( !v.form(), 'Invalid form' );
	$("#optionxa").attr("selected", true);
	ok( !v.form(), 'Invalid form' );
	$("#optionxb").attr("selected", true);
	ok( v.form(), 'Valid form' );
});

test("form(): with equalTo", function() {
	expect( 2 );
	var form = $('#testForm5')[0];
	var v = $(form).validate();
	ok( !v.form(), 'Invalid form' );
	$('#x1, #x2').val("hi");
	ok( v.form(), 'Valid form' );
});

test("check(): simple", function() {
	expect( 3 );
	var element = $('#firstname')[0];
	var v = $('#testForm1').validate();
	ok( v.size() == 0, 'No errors yet' );
	v.check(element);
	ok( v.size() == 1, 'error exists' );
	v.errorList = [];
	$('#firstname').val("hi");
	v.check(element);
	ok( !v.size() == 1, 'No more errors' );
});

test("hide(): input", function() {
	expect( 3 );
	var errorLabel = $('#errorFirstname');
	var element = $('#firstname')[0];
	element.value ="bla";
	var v = $('#testForm1').validate();
	errorLabel.show();
	ok( errorLabel.is(":visible"), "Error label visible before validation" );
	ok( v.element(element) );
	ok( errorLabel.is(":hidden"), "Error label not visible after validation" );
});

test("hide(): radio", function() {
	expect( 2 );
	var errorLabel = $('#agreeLabel');
	var element = $('#agb')[0];
	element.checked = true;
	var v = $('#testForm2').validate({ errorClass: "xerror" });
	errorLabel.show();
	ok( errorLabel.is(":visible"), "Error label visible after validation" );
	v.element(element);
	ok( errorLabel.is(":hidden"), "Error label not visible after hiding it" );
});

test("hide(): errorWrapper", function() {
	expect(2);
	var errorLabel = $('#errorWrapper');
	var element = $('#meal')[0];
	element.selectedIndex = 1;
	
	errorLabel.show();
	ok( errorLabel.is(":visible"), "Error label visible after validation" );
	var v = $('#testForm3').validate({ wrapper: "li", errorLabelContainer: $("#errorContainer") });
	v.element(element);
	ok( errorLabel.is(":hidden"), "Error label not visible after hiding it" );
});

test("hide(): container", function() {
	expect(5);
	var errorLabel = $('#errorContainer');
	var element = $('#testForm3')[0];
	ok( errorLabel.is(":hidden"), "Error label not visible at start" );
	var v = $('#testForm3').validate({ errorWrapper: "li", errorContainer: $("#errorContainer") });
	v.form();
	ok( errorLabel.is(":visible"), "Error label visible after validation" );
	$('#meal')[0].selectedIndex = 1;
	v.form();
	ok( errorLabel.is(":hidden"), "Error label not visible after hiding it" );
	$('#meal')[0].selectedIndex = -1;
	v.element("#meal");
	ok( errorLabel.is(":visible"), "Error label visible after validation" );
	$('#meal')[0].selectedIndex = 1;
	v.element("#meal");
	ok( errorLabel.is(":hidden"), "Error label not visible after hiding it" );
});

test("valid()", function() {
	expect(4);
	var errorList = [{name:"meal",message:"foo", element:$("#meal")[0]}];
	var v = $('#testForm3').validate();
	ok( v.valid(), "No errors, must be valid" );
	v.errorList = errorList;
	ok( !v.valid(), "One error, must be invalid" );
	reset();
	v = $('#testForm3').validate({ submitHandler: function() {
		ok( false, "Submit handler was called" );
	}});
	ok( v.valid(), "No errors, must be valid and returning true, even with the submit handler" );
	v.errorList = errorList;
	ok( !v.valid(), "One error, must be invalid, no call to submit handler" );
});

test("showErrors()", function() {
	expect( 4 );
	var errorLabel = $('#errorFirstname').hide();
	var element = $('#firstname')[0];
	var v = $('#testForm1').validate();
	ok( errorLabel.is(":hidden") );
	equals( 0, $("label.error[@for=lastname]").size() );
	v.showErrors({"firstname": "required", "lastname": "bla"});
	equals( true, errorLabel.is(":visible") );
	equals( true, $("label.error[@for=lastname]").is(":visible") );
});

test("showErrors(), allow empty string and null as default message", function() {
	$("#userForm").validate({
		rules: {
			username: {
				required: true,
				minlength: 3	
			}
		},
		messages: {
			username: {
				required: "",
				minlength: "too short"
			}	
		}
	});
	ok( !$("#username").valid() );
	equals( "", $("label.error[@for=username]").text() );
	
	$("#username").val("ab");
	ok( !$("#username").valid() );
	equals( "too short", $("label.error[@for=username]").text() );
	
	$("#username").val("abc");
	ok( $("#username").valid() );
	ok( $("label.error[@for=username]").is(":hidden") );
});

test("showErrors() - external messages", function() {
	expect( 4 );
	var methods = $.extend({}, $.validator.methods);
	var messages = $.extend({}, $.validator.messages);
	$.validator.addMethod("foo", function() { return false; });
	$.validator.addMethod("bar", function() { return false; });
	equals( 0, $("#testForm4 label.error[@for=f1]").size() );
	equals( 0, $("#testForm4 label.error[@for=f2]").size() );
	var form = $('#testForm4')[0];
	var v = $(form).validate({
		messages: {
			f1: "Please!",
			f2: "Wohoo!"
		}
	});
	v.form();
	equals( "Please!", $("#testForm4 label.error[@for=f1]").text() );
	equals( "Wohoo!", $("#testForm4 label.error[@for=f2]").text() );
	
	$.validator.methods = methods;
	$.validator.messages = messages;
});

test("showErrors() - custom handler", function() {
	expect(5);
	var v = $('#testForm1').validate({
		showErrors: function(errorMap, errorList) {
			equals( v, this );
			equals( v.errorList, errorList );
			equals( v.errorMap, errorMap );
			equals( "buga", errorMap.firstname );
			equals( "buga", errorMap.lastname );
		}
	});
	v.form();
});

test("option: (un)highlight, default", function() {
	expect(3);
	$("#testForm1").validate();
	var e = $("#firstname")
	ok( !e.hasClass("error") );
	e.valid()
	ok( e.hasClass("error") );
	e.val("hithere").valid()
	ok( !e.hasClass("error") );
});

test("option: (un)highlight, custom", function() {
	expect(6);
	$("#testForm1").validate({
		highlight: function(element, errorClass) {
			equals( "invalid", errorClass );
			$(element).hide();
		},
		unhighlight: function(element, errorClass) {
			equals( "invalid", errorClass )
			$(element).show();
		},
		errorClass: "invalid"
	});
	var e = $("#firstname")
	ok( e.is(":visible") );
	e.valid()
	ok( !e.is(":visible") );
	e.val("hithere").valid()
	ok( e.is(":visible") );
});

test("rules() - internal - input", function() {
	expect(4);
	var element = $('#firstname')[0];
	var v = $('#testForm1').validate();
	var rule = v.rules(element);
	equals( "required", rule[0].method );
	equals( true, rule[0].parameters );
	equals( "minlength", rule[1].method );
	equals( 2, rule[1].parameters );
});

test("rules() - internal - select", function() {
	expect(2);
	var element = $('#meal')[0];
	var v = $('#testForm3').validate();
	var rule = v.rules(element);
	equals( "required", rule[0].method );
	ok( rule[0].parameters );
});

test("rules() - external", function() {
	expect( 4 );
	var element = $('#text1')[0];
	var v = $('#form').validate({
		rules: {
			action: {date: true, min: 5}
		}
	});
	var rule = v.rules(element);
	equals( "date", rule[0].method );
	ok( rule[0].parameters );
	equals( "min", rule[1].method );
	equals( 5, rule[1].parameters );
});

test("rules() - external - complete form", function() {
	expect(1);
	
	var methods = $.extend({}, $.validator.methods);
	var messages = $.extend({}, $.validator.messages);
	
	$.validator.addMethod("verifyTest", function() {
		ok( true, "method executed" );
		return true;
	});
	var element = $('#text1')[0];
	var v = $('#form').validate({
		rules: {
			action: {verifyTest: true}
		}
	});
	v.form();
	
	$.validator.methods = methods;
	$.validator.messages = messages;
});

test("rules() - internal - input", function() {
	expect(7);
	var element = $('#form8input')[0];
	var v = $('#testForm8').validate();
	var rule = v.rules(element);
	equals( "required", rule[0].method );
	equals( true, rule[0].parameters );
	equals( "number", rule[1].method );
	equals( true, rule[1].parameters );
	equals( "rangelength", rule[2].method );
	equals( 2, rule[2].parameters[0] );
	equals( 8, rule[2].parameters[1] );
});

test("rules(), merge min/max to range, minlength/maxlength to rangelength", function() {
	var v = $("#testForm1clean").validate({
		rules: {
			firstname: {
				min: 5,
				max: 12	
			},
			lastname: {
				minlength: 2,
				maxlength: 8
			}
		}
	});
	var rangeRules = v.rules($("#firstnamec")[0]);
	equals( "range", rangeRules[0].method );
	equals( 5, rangeRules[0].parameters[0] );
	equals( 12, rangeRules[0].parameters[1] );
	
	var lengthRules = v.rules($("#lastnamec")[0]);
	equals( "rangelength", lengthRules[0].method );
	equals( 2, lengthRules[0].parameters[0] );
	equals( 8, lengthRules[0].parameters[1] );
});

test("rules(), evaluate dynamic parameters", function() {
	expect(3);
	var v = $("#testForm1clean").validate({
		rules: {
			firstname: {
				min: function(element) {
					equals( $("#firstnamec")[0], element );
					return 12;
				}
			}
		}
	});
	var rules = v.rules($("#firstnamec")[0]);
	equals( "min", rules[0].method );
	equals( 12, rules[0].parameters );
});

test("rules(), class and attribute combinations", function() {
	function compare(a, b, msg) {
		var ret = true;
		if ( a && b && a.length != undefined && a.length == b.length ) {
			for ( var i = 0; i < a.length; i++ )
				for(var key in a[i]) {
					if (a[i][key].length) {
						for (var arrayKey in a[i][key]) {
							if (a[i][key][arrayKey] != b[i][key][arrayKey]) {
								ret = false;
							}
						}
					} else if (a[i][key] != b[i][key]) {
						ret = false
					}
				}
		} else
			ret = false;
		ok( ret, msg + " expected: " + serialArray(b) + " result: " + serialArray(a) );
	}
	$.validator.addMethod("customMethod1", function() {
		return false;
	}, "");
	$.validator.addMethod("customMethod2", function() {
		return false;
	}, "");
	$("#v2").validate({
		rules: {
			'v2-i7': {
				required: true,
				minlength: 2,
				customMethod: true
			}
		}
	});
	compare( $("#v2-i1").rules(), [{ method: "required", parameters: true }]);
	compare( $("#v2-i2").rules(), [{ method: "required", parameters: true }, { method: "email", parameters: true }]);
	compare( $("#v2-i3").rules(), [{ method: "url", parameters: true }]);
	compare( $("#v2-i4").rules(), [{ method: "required", parameters: true }, { method: "minlength", parameters: 2 }]);
	compare( $("#v2-i5").rules(), [{ method: "required", parameters: true }, { method: "customMethod1", parameters: "123" }, { method: "rangelength", parameters: [2, 5] }]);
	compare( $("#v2-i6").rules(), [{ method: "required", parameters: true }, { method: "customMethod2", parameters: true }, { method: "rangelength", parameters: [2, 5] }]);
	compare( $("#v2-i7").rules(), [{ method: "required", parameters: true }, { method: "minlength", parameters: 2 }, { method: "customMethod", parameters: true }]);
	
	delete $.validator.methods.customMethod1;
	delete $.validator.messages.customMethod1;
	delete $.validator.methods.customMethod2;
	delete $.validator.messages.customMethod2;
});

test("defaultMessage(), empty title is ignored", function() {
	var v = $("#userForm").validate();
	equals( "This field is required.", v.defaultMessage($("#username")[0], "required") );
});

test("formatAndAdd", function() {
	expect(4);
	var v = $("#form").validate();
	var fakeElement = { form: { id: "foo" }, name: "bar" };
	v.formatAndAdd(fakeElement, {method: "maxLength", parameters: 2})
	equals( "Please enter no more than 2 characters.", v.errorList[0].message );
	equals( "bar", v.errorList[0].element.name );
	
	v.formatAndAdd(fakeElement, {method: "rangeValue", parameters:[2,4]})
	equals( "Please enter a value between 2 and 4.", v.errorList[1].message );
	
	v.formatAndAdd(fakeElement, {method: "rangeValue", parameters:[0,4]})
	equals( "Please enter a value between 0 and 4.", v.errorList[2].message );
});

test("formatAndAdd2", function() {
	expect(3);
	var v = $("#form").validate();
	var fakeElement = { form: { id: "foo" }, name: "bar" };
	jQuery.validator.messages.test1 = function(param, element) {
		equals( v, this );
		equals( 0, param );
		return "element " + element.name + " is not valid";
	};
	v.formatAndAdd(fakeElement, {method: "test1", parameters: 0})
	equals( "element bar is not valid", v.errorList[0].message );
});

test("error containers, simple", function() {
	expect(14);
	var container = $("#simplecontainer");
	var v = $("#form").validate({
		errorLabelContainer: container,
		showErrors: function() {
			container.find("h3").html( jQuery.format("There are {0} errors in your form.", this.size()) );
			this.defaultShowErrors();
		}
	});
	
	v.prepareForm();
	ok( v.valid(), "form is valid" );
	equals( 0, container.find("label").length, "There should be no error labels" );
	equals( "", container.find("h3").html() );
	
	v.prepareForm();
	v.errorList = [{message:"bar", element: {name:"foo"}}, {message: "necessary", element: {name:"required"}}];
	ok( !v.valid(), "form is not valid after adding errors manually" );
	v.showErrors();
	equals( 2, container.find("label").length, "There should be two error labels" );
	ok( container.is(":visible"), "Check that the container is visible" );
	container.find("label").each(function() {
		ok( $(this).is(":visible"), "Check that each label is visible" );
	});
	equals( "There are 2 errors in your form.", container.find("h3").html() );
	
	v.prepareForm();
	ok( v.valid(), "form is valid after a reset" );
	v.showErrors();
	equals( 2, container.find("label").length, "There should still be two error labels" );
	ok( container.is(":hidden"), "Check that the container is hidden" );
	container.find("label").each(function() {
		ok( $(this).is(":hidden"), "Check that each label is hidden" );
	});
});

test("error containers, with labelcontainer", function() {
	expect(28);
	var container = $("#container"),
		labelcontainer = $("#labelcontainer");
	var v = $("#form").validate({
		errorContainer: container,
		errorLabelContainer: labelcontainer,
		wrapper: "li"
	});
	
	v.prepareForm();
	ok( v.valid(), "form is valid" );
	equals( 0, container.find("label").length, "There should be no error labels in the container" );
	equals( 0, labelcontainer.find("label").length, "There should be no error labels in the labelcontainer" );
	equals( 0, labelcontainer.find("li").length, "There should be no lis labels in the labelcontainer" );
	
	v.prepareForm();
	v.errorList = [{message:"bar", element: {name:"foo"}}, {name: "required", message: "necessary", element: {name:"required"}}];
	ok( !v.valid(), "form is not valid after adding errors manually" );
	v.showErrors();
	equals( 0, container.find("label").length, "There should be no error label in the container" );
	equals( 2, labelcontainer.find("label").length, "There should be two error labels in the labelcontainer" );
	equals( 2, labelcontainer.find("li").length, "There should be two error lis in the labelcontainer" );
	ok( container.is(":visible"), "Check that the container is visible" );
	ok( labelcontainer.is(":visible"), "Check that the labelcontainer is visible" );
	labelcontainer.find("label").each(function() {
		ok( $(this).is(":visible"), "Check that each label is visible" );
		equals( "li", $(this).parent()[0].tagName.toLowerCase(), "Check that each label is wrapped in an li" );
		ok( $(this).parent("li").is(":visible"), "Check that each parent li is visible" );
	});
	
	
	v.prepareForm();
	ok( v.valid(), "form is not valid after adding errors manually" );
	v.showErrors();
	equals( 0, container.find("label").length, "There should be no error label in the container" );
	equals( 2, labelcontainer.find("label").length, "There should be two error labels in the labelcontainer" );
	equals( 2, labelcontainer.find("li").length, "There should be two error lis in the labelcontainer" );
	ok( container.is(":hidden"), "Check that the container is hidden" );
	ok( labelcontainer.is(":hidden"), "Check that the labelcontainer is hidden" );
	labelcontainer.find("label").each(function() {
		ok( $(this).is(":hidden"), "Check that each label is visible" );
		equals( "li", $(this).parent()[0].tagName.toLowerCase(), "Check that each label is wrapped in an li" );
		ok( $(this).parent("li").is(":hidden"), "Check that each parent li is visible" );
	});
});

test("errorcontainer, show/hide only on submit", function() {
	expect(12);
	var container = $("#container");
	var labelContainer = $("#labelcontainer");
	var v = $("#testForm1").bind("invalid-form.validate", function() {
		ok( true, "invalid-form event triggered called" );
	}).validate({
		errorContainer: container,
		errorLabelContainer: labelContainer,
		showErrors: function() {
			container.html( jQuery.format("There are {0} errors in your form.", this.numberOfInvalids()) );
			ok( true, "showErrors called" );
			this.defaultShowErrors();
		}
	});
	equals( "", container.html(), "must be empty" );
	equals( "", labelContainer.html(), "must be empty" );
	// validate whole form, both showErrors and invalidHandler must be called once
	// preferably invalidHandler first, showErrors second
	ok( !v.form(), "invalid form" );
	equals( 2, labelContainer.find("label").length );
	equals( "There are 2 errors in your form.", container.html() );
	ok( labelContainer.is(":visible"), "must be visible" );
	ok( container.is(":visible"), "must be visible" );
	
	$("#firstname").val("hix").keyup();
	$("#testForm1").triggerHandler("keyup", [jQuery.event.fix({ type: "keyup", target: $("#firstname")[0] })]);
	equals( 1, labelContainer.find("label:visible").length );
	equals( "There are 1 errors in your form.", container.html() );
});

test("findByName()", function() {
	isSet( new $.validator({}, document.getElementById("form")).findByName(document.getElementById("radio1").name), $("#form").find("[name=radio1]") );
});

test("focusInvalid()", function() {
	expect(1);
	var inputs = $("#testForm1 input").focus(function() {
		equals( inputs[0], this, "focused first element" );
	});
	var v = $("#testForm1").validate();
	v.form();
	// have to explicitly show input elements with error class, they are hidden by testsuite styles
	inputs.show();
	v.focusInvalid();
});

test("findLastActive()", function() {
	expect(3);
	var v = $("#testForm1").validate();
	ok( !v.findLastActive() );
	v.form();
	v.focusInvalid();
	ok( !v.findLastActive() );
	try {
		$("#testForm1").triggerEvent("focusin", $("#testForm1 input:last")[0]);
		v.focusInvalid();
		equals( lastInput, v.findLastActive() );
	} catch(e) {
		ok( true, "Ignore in IE" );
	}
});

test("validating multiple checkboxes with 'required'", function() {
	expect(3);
	var checkboxes = $("#form input[@name=check3]").attr("checked", false);
	equals(5, checkboxes.size());
	var v = $("#form").validate({
		rules: {
			check3: "required"
		}
	});
	v.form();
	equals(1, v.size());
	checkboxes.filter(":last").attr("checked", true);
	v.form();
	equals(0, v.size());
});

test("refresh()", function() {
	var counter = 0;
	function add() {
		$("<input class='{required:true}' name='list" + counter++ + "' />").appendTo("#testForm2");
	}
	function errors(expected, message) {
		equals(expected, v.size(), message );
	}
	var v = $("#testForm2").validate();
	v.form();
	errors(1);
	add();
	v.form();
	errors(2);
	add();
	v.form();
	errors(3);
	$("#testForm2 input[@name=list1]").remove();
	v.form();
	errors(2);
	add();
	v.form();
	errors(3);
	$("#testForm2 input[@name^=list]").remove();
	v.form();
	errors(1);
	$("#agb").attr("disabled", true);
	v.form();
	errors(0);
	$("#agb").attr("disabled", false);
	v.form();
	errors(1);
});

test("idOrName()", function() {
	expect(4);
	var v = $("#testForm1").validate();
	equals( "form8input", v.idOrName( $("#form8input")[0] ) );
	equals( "check", v.idOrName( $("#form6check1")[0] ) );
	equals( "agree", v.idOrName( $("#agb")[0] ) );
	equals( "button", v.idOrName( $("#form :button")[0] ) );
});

test("resetForm()", function() {
	function errors(expected, message) {
		equals(expected, v.size(), message );
	}
	var v = $("#testForm1").validate();
	v.form();
	errors(2);
	$("#firstname").val("hiy");
	v.resetForm();
	errors(0);
	equals("", $("#firstname").val(), "form plugin is included, therefor resetForm must also reset inputs, not only errors");
});

test("ajaxSubmit", function() {
	expect(1);
	stop();
	$("#user").val("Peter");
	$("#password").val("foobar");
	jQuery("#signupForm").validate({
		submitHandler: function(form) {
			jQuery(form).ajaxSubmit({
				success: function(response) {
					equals("Hi Peter, welcome back.", response);
					start();
				}
			});
		}
	});
	jQuery("#signupForm").triggerHandler("submit");
});


module("misc");

test("success option", function() {
	expect(7);
	equals( "", $("#firstname").val() );
	var v = $("#testForm1").validate({
		success: "valid"
	});
	var label = $("#testForm1 label");
	ok( label.is(".error") );
	ok( !label.is(".valid") );
	v.form();
	ok( label.is(".error") );
	ok( !label.is(".valid") );
	$("#firstname").val("hi");
	v.form();
	ok( label.is(".error") );
	ok( label.is(".valid") );
});

test("success option2", function() {
	expect(5);
	equals( "", $("#firstname").val() );
	var v = $("#testForm1").validate({
		success: "valid"
	});
	var label = $("#testForm1 label");
	ok( label.is(".error") );
	ok( !label.is(".valid") );
	$("#firstname").val("hi");
	v.form();
	ok( label.is(".error") );
	ok( label.is(".valid") );
});

test("success option3", function() {
	expect(5);
	equals( "", $("#firstname").val() );
	$("#errorFirstname").remove();
	var v = $("#testForm1").validate({
		success: "valid"
	});
	equals( 0, $("#testForm1 label").size() );
	$("#firstname").val("hi");
	v.form();
	var labels = $("#testForm1 label");
	equals( 2, labels.size() );
	ok( labels.eq(0).is(".valid") );
	ok( !labels.eq(1).is(".valid") );
});

test("successlist", function() {
	var v = $("#form").validate({ success: "xyz" });
	v.form();
	equals(0, v.successList.length);
});

test("messages", function() {
	var m = jQuery.validator.messages;
	equals( "Please enter no more than 0 characters.", m.maxLength(0) );
	equals( "Please enter at least 1 characters.", m.minLength(1) );
	equals( "Please enter a value between 1 and 2 characters long.", m.rangeLength([1, 2]) );
	equals( "Please enter a value less than or equal to 1.", m.maxValue(1) );
	equals( "Please enter a value greater than or equal to 0.", m.minValue(0) );
	equals( "Please enter a value between 1 and 2.", m.rangeValue([1, 2]) );
});

test("jQuery.format", function() {
	equals( "Please enter a value between 0 and 1.", jQuery.format("Please enter a value between {0} and {1}.", 0, 1) );
	equals( "0 is too fast! Enter a value smaller then 0 and at least -15", jQuery.format("{0} is too fast! Enter a value smaller then {0} and at least {1}", 0, -15) );
	var template = jQuery.format("{0} is too fast! Enter a value smaller then {0} and at least {1}");
	equals( "0 is too fast! Enter a value smaller then 0 and at least -15", template(0, -15) );
	template = jQuery.format("Please enter a value between {0} and {1}.");
	equals( "Please enter a value between 1 and 2.", template([1, 2]) );
});

test("option: ignore", function() {
	var v = $("#testForm1").validate({
		ignore: "[@name=lastname]"
	});
	v.form();
	equals( 1, v.size() );
});

test("option: subformRequired", function() {
	jQuery.validator.addMethod("billingRequired", function(value, element) {
		if ($("#bill_to_co").is(":checked"))
			return $(element).parents("#subform").length;
		return !this.optional(element);
	}, "");
	var v = $("#subformRequired").validate();
	v.form();
	equals( 1, v.size() );
	$("#bill_to_co").attr("checked", false);
	v.form();
	equals( 2, v.size() );
	
	delete $.validator.methods.billingRequired;
	delete $.validator.messages.billingRequired;
});

module("expressions");

test("expression: :blank", function() {
	var e = $("#lastname")[0];
	equals( 1, $(e).filter(":blank").length );
	e.value = " ";
	equals( 1, $(e).filter(":blank").length );
	e.value = "   "
	equals( 1, $(e).filter(":blank").length );
	e.value= " a ";
	equals( 0, $(e).filter(":blank").length );
});

test("expression: :filled", function() {
	var e = $("#lastname")[0];
	equals( 0, $(e).filter(":filled").length );
	e.value = " ";
	equals( 0, $(e).filter(":filled").length );
	e.value = "   "
	equals( 0, $(e).filter(":filled").length );
	e.value= " a ";
	equals( 1, $(e).filter(":filled").length );
});

test("expression: :unchecked", function() {
	var e = $("#check2")[0];
	equals( 1, $(e).filter(":unchecked").length );
	e.checked = true;
	equals( 0, $(e).filter(":unchecked").length );
	e.checked = false;
	equals( 1, $(e).filter(":unchecked").length );
});

module("events");

test("validate on blur", function() {
	function errors(expected, message) {
		equals(expected, v.size(), message );
	}
	function blur(target) {
		$("#testForm1").triggerEvent("focusout", target[0]);
	}
	var e = $("#firstname");
	var v = $("#testForm1").validate();
	blur(e);
	errors(0, "No value yet, required is skipped on blur");
	e.val("h");
	blur(e);
	errors(1, "Required was ignored, but as something was entered, check other rules, minLength isn't met");
	e.val("hh");
	e.blur(0, "All is fine");
	e.val("");
	v.form();
	errors(2, "Submit checks all rules, both fields invalid");
	blur(e);
	errors(1, "Blurring the field results in emptying the error list first, then checking the invalid field: its still invalid, don't remove the error" );
	e.val("h");
	blur(e);
	errors(1, "Entering a single character fulfills required, but not minLength: 2, still invalid");
	e.val("hh");
	blur(e);
	errors(0, "Both required and minLength are met, no errors left");
});

test("validate on keyup", function() {
	function errors(expected, message) {
		equals(expected, v.size(), message );
	}
	function keyup(target) {
		$("#testForm1").triggerEvent("keyup", target[0]);
	}
	var e = $("#firstname");
	var v = $("#testForm1").validate();
	keyup(e);
	errors(0, "No value, no errors");
	e.val("a");
	keyup(e);
	errors(0, "Value, but not invalid");
	e.val("");
	v.form();
	errors(2, "Both invalid");
	keyup(e);
	errors(1, "Only one field validated, still invalid");
	e.val("hh");
	keyup(e);
	errors(0, "Not invalid anymore");
	e.val("h");
	keyup(e);
	errors(1, "Field didn't loose focus, so validate again, invalid");
	e.val("hh");
	keyup(e);
	errors(0, "Valid");
});

test("validate on not keyup, only blur", function() {
	function errors(expected, message) {
		equals(expected, v.size(), message );
	}
	var e = $("#firstname");
	var v = $("#testForm1").validate({
		onkeyup: false
	});
	errors(0);
	e.val("a");
	$("#testForm1").triggerEvent("keyup", e[0]);
	e.keyup();
	errors(0);
	$("#testForm1").triggerEvent("focusout", e[0]);
	errors(1);
});

test("validate on keyup and blur", function() {
	function errors(expected, message) {
		equals(expected, v.size(), message );
	}
	var e = $("#firstname");
	var v = $("#testForm1").validate();
	errors(0);
	e.val("a");
	$("#testForm1").triggerEvent("keyup", e[0]);
	errors(0);
	$("#testForm1").triggerEvent("focusout", e[0]);
	errors(1);
});

test("validate email on keyup and blur", function() {
	function errors(expected, message) {
		equals(expected, v.size(), message );
	}
	var e = $("#firstname");
	var v = $("#testForm1").validate();
	v.form();
	errors(2);
	e.val("a");
	$("#testForm1").triggerEvent("keyup", e[0]);
	errors(1);
	e.val("aa");
	$("#testForm1").triggerEvent("keyup", e[0]);
	errors(0);
});

test("validate checkbox on click", function() {
	function errors(expected, message) {
		equals(expected, v.size(), message );
	}
	function trigger(element) {
		element[0].click();
		$("#form").triggerEvent("click", element[0]);
	}
	var e = $("#check2");
	var v = $("#form").validate({
		rules: {
			check2: "required"
		}
	});
	trigger(e);
	errors(0);
	trigger(e);
	equals( false, v.form() );
	errors(1);
	trigger(e);
	errors(0);
	trigger(e);
	errors(1);
});

test("validate multiple checkbox on click", function() {
	function errors(expected, message) {
		equals(expected, v.size(), message );
	}
	function trigger(element) {
		element[0].click();
		$("#form").triggerEvent("click", element[0]);
	}
	var e1 = $("#check1").attr("checked", false);
	var e2 = $("#check1b");
	var v = $("#form").validate({
		rules: {
			check: {
				required: true,
				minLength: 2
			}
		}
	});
	trigger(e1);
	errors(0, "Minlength must be skipped");
	trigger(e2);
	errors(0);
	trigger(e2);
	equals( false, v.form() );
	errors(1);
	trigger(e2);
	errors(0);
	trigger(e2);
	errors(1);
});

test("validate radio on click", function() {
	function errors(expected, message) {
		equals(expected, v.size(), message );
	}
	function trigger(element) {
		element[0].click();
		$("#form").triggerEvent("click", element[0]);
	}
	var e1 = $("#radio1");
	var e2 = $("#radio1a");
	var v = $("#form").validate({
		rules: {
			radio1: "required"
		}
	});
	errors(0);
	equals( false, v.form() );
	errors(1);
	trigger(e2);
	errors(0);
	trigger(e1);
	errors(0);
});

module("ajax");

test("check the serverside script works", function() {
	stop();
	$.getJSON("users.php", {value: 'asd'}, function(response) {
		ok( response, "yet available" );
		$.getJSON("users.php", {username: "asdf"}, function(response) {
			ok( !response, "already taken" );
			start();
		});
	});
});

test("check the serverside script works2", function() {
	stop();
	$.getJSON("users2.php", {value: 'asd'}, function(response) {
		ok( response, "yet available" );
		$.getJSON("users.php", {username: "asdf"}, function(response) {
			ok( !response, "asdf is already taken, please try something else" );
			start();
		});
	});
});

test("validate via remote method", function() {
	expect(5);
	stop();
	var e = $("#username");
	var v = $("#userForm").validate({
		rules: {
			username: {
				required: true,
				remote: "users.php"
			}
		},
		messages: {
			username: {
				required: "Please",
				remote: jQuery.format("{0} in use")
			}
		},
		submitHandler: function() {
			ok( false, "submitHandler may never be called when validating only elements");
		}
	});
	$().ajaxStop(function() {
		ok( true, "There needs to be exactly one request." );
		equals( 1, v.size(), "There must be one error" );
		equals( "asdf in use", v.errorList[0].message );
		$().unbind("ajaxStop");
		start();
	});
	ok( !v.element(e), "invalid element, nothing entered yet" );
	e.val("asdf");
	ok( !v.element(e), "still invalid, because remote validation must block until it returns" );
});

test("validate via remote method and serverside message", function() {
	expect(5);
	stop();
	var e = $("#username");
	var v = $("#userForm").validate({
		rules: {
			username: {
				required: true,
				remote: "users2.php"
			}
		},
		messages: {
			username: {
				required: "Please"
			}
		},
		submitHandler: function() {
			ok( false, "submitHandler may never be called when validating only elements");
		}
	});
	$().ajaxStop(function() {
		ok( true, "There needs to be exactly one request." );
		equals( 1, v.size(), "There must be one error" );
		equals( "asdf is already taken, please try something else", v.errorList[0].message );
		$().unbind("ajaxStop");
		start();
	});
	ok( !v.element(e), "invalid element, nothing entered yet" );
	e.val("asdf");
	ok( !v.element(e), "still invalid, because remote validation must block until it returns" );
});
