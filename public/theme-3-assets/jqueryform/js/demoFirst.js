"use strict";
jQuery(function ($) {
    var fields = [
    //     {
    //     type: 'autocomplete',
    //     label: 'Custom Autocomplete',
    //     required: true,
    //     values: [
    //         { label: 'SQL' },
    //         { label: 'C#' },
    //         { label: 'JavaScript' },
    //         { label: 'Java' },
    //         { label: 'Python' },
    //         { label: 'C++' },
    //         { label: 'PHP' },
    //         { label: 'Swift' },
    //         { label: 'Ruby' }
    //     ]
    // },
    // {
    //     label: 'Star Rating',
    //     attrs: {
    //         type: 'starRating',
    //         number_of_star: 5
    //     },
    //     icon: '🌟'
    // }
    ];



    // var actionButtons = [{
    //     id: 'smile',
    //     className: 'btn btn-success',
    //     label: '😁',
    //     type: 'button',
    //     events: {
    //         click: function () {
    //             alert('😁😁😁 !SMILE! 😁😁😁');
    //         }
    //     }
    // }];

    // var options = {
    //     disabledAttrs: [
    //         'className'
    //     ]
    // };
    var templates = {
        starRating: function (fieldData) {
            return {
                field: '<span id="' + fieldData.name + '">',
                onRender: function () {
                    $(document.getElementById(fieldData.name)).rateYo({ rating: fieldData.value, numStars: fieldData.number_of_star, halfStar: true, precision: 2 });
                }
            };
        }
    };

    var inputSets = [{
        label: 'User Details',
        icon: '👨',
        fields: [{
            type: 'text',
            label: 'First Name',
            className: 'form-control'
        }, {
            type: 'select',
            label: 'Profession',
            className: 'form-control',
            values: [{
                label: 'Street Sweeper',
                value: 'option-2',
                selected: false
            }, {
                label: 'Brain Surgeon',
                value: 'option-3',
                selected: false
            }]
        }, {
            type: 'textarea',
            label: 'Short Bio:',
            className: 'form-control'
        }]
    }, {
        label: 'User Agreement',
        fields: [
        //     {
        //     type: 'header',
        //     subtype: 'h3',
        //     label: 'Terms & Conditions',
        //     className: 'header'
        // }, 
        {
            type: 'paragraph',
            label: 'Leverage agile frameworks to provide a robust synopsis for high level overviews. Iterative approaches to corporate strategy foster collaborative thinking to further the overall value proposition. Organically grow the holistic world view of disruptive innovation via workplace diversity and empowerment.',
        }, 
        {
            type: 'paragraph',
            label: 'Bring to the table win-win survival strategies to ensure proactive domination. At the end of the day, going forward, a new normal that has evolved from generation X is on the runway heading towards a streamlined cloud solution. User generated content in real-time will have multiple touchpoints for offshoring.',
        },
         {
            type: 'checkbox',
            label: 'Do you agree to the terms and conditions?',
        }]
    }];

    var typeUserDisabledAttrs = {};


    var newAttributes = {
        column: {
            label: 'Columns',
            options: {
                '1': '1 Column',
                '2': '2 Column',
                '3': '3 Column',

            },
        }
    };


    var typeUserAttrs = {};
    const fieldss = ["autocomplete", "button", "checkbox-group", "file", "paragraph", "date", "number", "radio-group", "select", "text", "textarea"];

    // const fieldss = ["autocomplete", "button", "checkbox-group", "file", "header", "paragraph", "date", "number", "radio-group", "select", "text", "textarea"];
    fieldss.forEach(function (item, index) {
	if(item == 'text'){
		typeUserAttrs[item] = {column:newAttributes.column,is_client_email:{label: 'Is Client Email',
                type: 'checkbox',
                value: '1',}};
	}else{
        	typeUserAttrs[item] = newAttributes;
        }
    });



    var disabledSubtypes = { textarea: ["tinymce", "quill"] };

    var disabledAttrs = ['placeholder'];


    var fbOptions = {
        subtypes: {
            text: ['datetime-local', 'email'],
            textarea: ['ckeditor']
        },
        typeUserEvents: {
            text: {
                onadd: function (fld) {
                    var $patternField = $(".fld-is_client_email", fld);
                    var $patternWrap = $patternField.parents(".is_client_email-wrap:eq(0)");
                  
                    var select = fld.querySelector(".fld-subtype");
                    
                    if(select.value != "email"){
                        $patternWrap.hide();
                    $patternField.prop("checked", false);
                    $patternField.prop("disabled", true);
                    }

                    
                
                    var val = $patternField.prop("checked")?1 : 0;
                    
                    if(val == 1){
                    $patternWrap.show();
                    $patternField.prop("checked", true);
                    $patternField.prop("disabled", false);
                    }
                    fld.querySelector(".fld-subtype").onchange = function (e) {
                        var toggle = e.target.value === "email";
                        if (e.target.value == 'email') {

                            $patternWrap.show(!toggle);
                            $patternField.prop("disabled", !toggle);
                            $patternField.prop("checked", !toggle);

                        } else {
                            $patternWrap.hide(!toggle);
                            $patternField.prop("disabled", !toggle);
                            $patternField.prop("checked", !toggle);
                        }
                       
                    };
                }
            }
        },
        onSave: function (e, formData) {
            toggleEdit();
            $('.render-wrap').formRender({
                formData: formData,
                templates: templates
            });
            window.sessionStorage.setItem('formData', JSON.stringify(formData));
        },
        onOpenFieldEdit: function (editPanel) {
            // alert('a field edit panel was opened');


        },
        stickyControls: {
            enable: true
        },
        sortableControls: true,
        fields: fields,
        templates: templates,
        inputSets: inputSets,
        typeUserDisabledAttrs: typeUserDisabledAttrs,
        typeUserAttrs: typeUserAttrs,
        // typeUserEvents: typeUserEvents,
        disableInjectedStyle: false,
        // actionButtons: actionButtons,
        disableFields: ['autocomplete','paragraph','button','header','hidden'],
        disabledSubtypes: disabledSubtypes,
        disabledFieldButtons: {
            text: ['copy']
        }
    };
    // alert(fbOptions.subtypes.text[1]);
    // fbOptions.subtypes.text[1] == 'email')
    var formData = window.sessionStorage.getItem('formData');
    // console.log(formData);
    var editing = true;

    if (formData) {
        fbOptions.formData = JSON.parse(formData);
    }

    function toggleEdit() {
        console.log('hi');
        document.body.classList.toggle('form-rendered', editing);
        return editing = !editing;
    }
    var setFormData = $("input[name='json']").val();
    if (setFormData.length) {
        fbOptions.formData = JSON.parse(setFormData);
    }



    var formBuilder = $('.build-wrap').formBuilder(fbOptions);
    var fbPromise = formBuilder.promise;

    fbPromise.then(function (fb) {
        var apiBtns = {
            showData: fb.actions.showData,
            clearFields: fb.actions.clearFields,
            getData: function () {
                console.log(fb.actions.getData());
            },
            setData: function () {
                fb.actions.setData(setFormData);
            },
            addField: function () {
                var field = {
                    type: 'text',
                    class: 'form-control',
                    label: 'Text Field added at: ' + new Date().getTime()
                };
                fb.actions.addField(field);
            },
            removeField: function () {
                fb.actions.removeField();
            },
            testSubmit: function () {
                var formData = new FormData(document.forms[0]);
                console.log('Can submit: ', document.forms[0].checkValidity());
                console.log('FormData:', formData);
                for (var pair of formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                }
            },
            resetDemo: function () {
                window.sessionStorage.removeItem('formData');
                location.reload();
            }
        };

        Object.keys(apiBtns).forEach(function (action) {
            document.getElementById(action)
                .addEventListener('click', function (e) {
                    apiBtns[action]();
                });
        });

        document.getElementById('getXML').addEventListener('click', function () {
            alert(formBuilder.actions.getData('xml'));
        });
        document.getElementById('getJSON').addEventListener('click', function () {
            var json = formBuilder.actions.getData('json', true);
            $("input[name='json']").val(json);
            $("#design-form").submit();
        });
        document.getElementById('getJS').addEventListener('click', function () {
            alert('check console');
            console.log(formBuilder.actions.getData());
        });
    });
});
