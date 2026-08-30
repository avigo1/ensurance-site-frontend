(function(){
  'use strict';

  var form = document.getElementById('ens-non-auto-request-form');
  if (!form) return;

  var steps = Array.prototype.slice.call(form.querySelectorAll('.nar-step'));
  var current = 0;
  var startedAt = Date.now();
  var coverageEl = document.getElementById('nar-coverage-type');
  var coverageLock = form.getAttribute('data-coverage-lock') || '';
  var partnerId = form.getAttribute('data-partner-id') || '';
  var referralCode = form.getAttribute('data-referral-code') || '';
  var progressFill = document.getElementById('nar-progress-fill');
  var progressCount = document.getElementById('nar-progress-count');
  var progressLabel = document.getElementById('nar-progress-label');
  var submitState = document.getElementById('nar-submit-state');
  var success = document.getElementById('nar-success');
  var review = document.getElementById('nar-review');

  var productLabels = {life:'Life',home:'Home',health:'Health',renters:'Renters'};

  function value(name){
    var el = form.elements[name];
    if (!el) return '';
    if (el.type === 'checkbox') return el.checked ? el.value : '';
    return String(el.value || '').trim();
  }

  function setError(name, message){
    var node = form.querySelector('[data-error-for="'+name+'"]');
    if (node) node.textContent = message || '';
  }

  function clearErrors(){
    form.querySelectorAll('.nar-error').forEach(function(el){el.textContent='';});
  }

  function coverage(){ return coverageLock || value('coverage_type'); }

  function syncProduct(){
    var c = coverage();
    form.querySelectorAll('.nar-product').forEach(function(group){
      group.classList.toggle('is-active', group.getAttribute('data-product') === c);
    });
    var heading = document.getElementById('nar-product-heading');
    if (heading && c) heading.textContent = 'A few '+(productLabels[c] || '')+' details help us route this correctly.';
  }

  function updateProgress(){
    var pct = ((current+1)/steps.length)*100;
    if (progressFill) progressFill.style.width = pct+'%';
    if (progressCount) progressCount.textContent = 'Step '+(current+1)+' of '+steps.length;
    var labels = ['Request','Details','Contact','Review'];
    if (progressLabel) progressLabel.textContent = labels[current] || 'Request';
  }

  function showStep(index){
    current = Math.max(0, Math.min(index, steps.length-1));
    steps.forEach(function(step,i){step.classList.toggle('is-active',i===current);});
    syncProduct();
    updateProgress();
    var card = form.closest('.nar-card');
    if (card) card.scrollIntoView({behavior:'smooth',block:'start'});
  }

  function validEmail(v){ return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); }
  function validPhone(v){ return v.replace(/\D/g,'').length >= 10; }

  function validateStep(index){
    clearErrors();
    var ok = true;
    var c = coverage();
    function req(name,msg){ if(!value(name)){setError(name,msg);ok=false;} }

    if(index===0){
      if(!c){setError('coverage_type','Choose the kind of insurance help you need.');ok=false;}
      req('state','Select your state.');
      if(!/^\d{5}$/.test(value('zip'))){setError('zip','Enter a 5-digit ZIP code.');ok=false;}
    }
    if(index===1){
      if(c==='life'){
        var age = parseInt(value('age'),10);
        if(!age || age<18 || age>100){setError('age','Enter an age between 18 and 100.');ok=false;}
        req('life_type','Choose an option.');req('coverage_amount','Choose an amount or Not sure.');req('tobacco_use','Choose an option.');req('coverage_timing','Choose a timing.');
      }else if(c==='home'){
        req('property_type','Choose a property type.');req('ownership_status','Choose a property status.');req('current_insurance','Choose an option.');req('home_coverage_timing','Choose a timing.');
      }else if(c==='renters'){
        req('renting_status','Choose a rental status.');req('renters_start_timing','Choose a timing.');
      }else if(c==='health'){
        req('health_request_type','Choose an option.');
        var size=parseInt(value('health_household_size'),10);if(!size||size<1||size>20){setError('health_household_size','Enter a number from 1 to 20.');ok=false;}
        req('health_coverage_timing','Choose a timing.');
      }
    }
    if(index===2){
      req('first_name','Enter your first name.');req('last_name','Enter your last name.');
      if(!validEmail(value('email'))){setError('email','Enter a valid email address.');ok=false;}
      if(!validPhone(value('phone'))){setError('phone','Enter a valid phone number.');ok=false;}
      req('preferred_contact','Choose a contact method.');
    }
    if(index===3 && !form.elements.consent.checked){setError('consent','Please confirm your authorization before submitting.');ok=false;}
    return ok;
  }

  function labelFor(name){
    var labels={coverage_type:'Insurance type',state:'State',zip:'ZIP code',age:'Age',life_type:'Life insurance type',coverage_amount:'Coverage amount',term_length:'Term length',tobacco_use:'Tobacco / nicotine',health_band:'Overall health',coverage_timing:'Coverage timing',property_type:'Property type',ownership_status:'Property status',year_built:'Year built',current_insurance:'Currently insured',claims_context:'Recent claims',home_coverage_timing:'Coverage timing',renting_status:'Rental status',renters_current_insurance:'Current renters insurance',renters_start_timing:'Coverage timing',health_request_type:'Health coverage need',health_household_size:'People needing coverage',health_coverage_timing:'Coverage timing',first_name:'First name',last_name:'Last name',email:'Email',phone:'Phone',preferred_contact:'Preferred contact'};
    return labels[name] || name;
  }

  function displayValue(name){
    var el=form.elements[name];if(!el)return '';
    if(el.tagName==='SELECT' && el.selectedIndex>=0)return el.options[el.selectedIndex].text;
    return value(name);
  }

  function buildReview(){
    if(!review)return;
    var c=coverage();
    var names=['coverage_type','state','zip'];
    if(c==='life')names=names.concat(['age','life_type','coverage_amount','term_length','tobacco_use','health_band','coverage_timing']);
    if(c==='home')names=names.concat(['property_type','ownership_status','year_built','current_insurance','claims_context','home_coverage_timing']);
    if(c==='renters')names=names.concat(['renting_status','renters_current_insurance','renters_start_timing']);
    if(c==='health')names=names.concat(['health_request_type','health_household_size','health_coverage_timing']);
    names=names.concat(['first_name','last_name','email','phone','preferred_contact']);
    var html='<div class="nar-review__section"><p class="nar-review__title">Request summary</p>';
    names.forEach(function(name){var v=displayValue(name);if(!v)return;if(name==='coverage_type'&&coverageLock)v=productLabels[coverageLock]+' Insurance';html+='<div class="nar-review__row"><span class="nar-review__label">'+escapeHtml(labelFor(name))+'</span><span class="nar-review__value">'+escapeHtml(v)+'</span></div>';});
    html+='</div>';
    review.innerHTML=html;
  }

  function escapeHtml(s){return String(s).replace(/[&<>'"]/g,function(c){return {'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[c];});}

  form.addEventListener('click',function(e){
    var next=e.target.closest('.nar-next');
    var back=e.target.closest('.nar-back');
    if(next){e.preventDefault();if(validateStep(current)){if(current===2)buildReview();showStep(current+1);}}
    if(back){e.preventDefault();showStep(current-1);}
  });

  if(coverageEl && !coverageLock){coverageEl.addEventListener('change',syncProduct);}

  form.addEventListener('submit',function(e){
    e.preventDefault();
    if(!validateStep(3))return;
    var button=form.querySelector('.nar-submit');
    var c=coverage();
    var payload={
      coverage_type:c,state:value('state'),zip:value('zip'),age:value('age'),first_name:value('first_name'),last_name:value('last_name'),email:value('email'),phone:value('phone'),preferred_contact:value('preferred_contact'),
      source_type:referralCode?'partner_referral':'direct',partner_id:'',referral_code:referralCode,consent:form.querySelector('#nar-consent')&&form.querySelector('#nar-consent').checked?'1':'',source_url:window.location.href,website:value('website'),elapsed_ms:Date.now()-startedAt,
      notes:value('notes'),life_type:value('life_type'),coverage_amount:value('coverage_amount'),term_length:value('term_length'),tobacco_use:value('tobacco_use'),health_band:value('health_band'),existing_coverage:value('existing_coverage'),coverage_timing:value('coverage_timing'),
      property_type:value('property_type'),ownership_status:value('ownership_status'),year_built:value('year_built'),current_insurance:value('current_insurance'),claims_context:value('claims_context'),home_coverage_timing:value('home_coverage_timing'),
      renting_status:value('renting_status'),renters_current_insurance:value('renters_current_insurance'),renters_start_timing:value('renters_start_timing'),health_request_type:value('health_request_type'),health_household_size:value('health_household_size'),health_coverage_timing:value('health_coverage_timing')
    };

    form.classList.add('nar-is-busy');
    button.disabled=true;
    submitState.textContent='Sending your request securely…';

    fetch((window.ensuranceNonAutoRequest && window.ensuranceNonAutoRequest.endpoint) || '/wp-json/ensurance/v1/insurance-request',{
      method:'POST',headers:{'Content-Type':'application/json'},credentials:'same-origin',body:JSON.stringify(payload)
    }).then(function(r){return r.json().then(function(j){return {ok:r.ok,status:r.status,json:j};});}).then(function(res){
      if(!res.ok)throw new Error((res.json&&res.json.message)||'We could not send your request. Please try again.');
      form.hidden=true;success.hidden=false;document.getElementById('nar-success-id').textContent=res.json.request_id||'';
    }).catch(function(err){submitState.textContent=err.message||'We could not send your request. Please try again.';}).finally(function(){form.classList.remove('nar-is-busy');button.disabled=false;});
  });

  syncProduct();updateProgress();
})();
