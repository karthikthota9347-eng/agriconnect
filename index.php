<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>AgriConnect</title>
<link rel="icon" href="assets/images/favicon.ico" type="image/x-icon"/>
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;800;900&display=swap" rel="stylesheet"/>
<style>
*{box-sizing:border-box;margin:0;padding:0}html,body{height:100%;font-family:'Nunito',sans-serif;overflow:hidden}body{background:#050e0a}
#splash{position:fixed;inset:0;z-index:100;display:flex;flex-direction:column;align-items:center;justify-content:center;background:radial-gradient(ellipse at 40% 30%,#0d2e1a,#050e0a 70%);transition:opacity .8s,visibility .8s}
#splash.hide{opacity:0;visibility:hidden;pointer-events:none}
.sp-logo{width:140px;height:140px;border-radius:36px;background:linear-gradient(145deg,#1a6b3a,#082010);border:2px solid rgba(55,214,122,.4);box-shadow:0 0 80px rgba(55,214,122,.2);display:flex;align-items:center;justify-content:center;font-size:58px;animation:pop .7s cubic-bezier(.34,1.56,.64,1) both}
.sp-name{margin-top:20px;font-size:40px;font-weight:900;background:linear-gradient(135deg,#37d67a,#a8ffcb);-webkit-background-clip:text;-webkit-text-fill-color:transparent;animation:up .6s .3s both}
.sp-tag{margin-top:6px;font-size:13px;color:rgba(255,255,255,.4);letter-spacing:3px;text-transform:uppercase;animation:up .6s .5s both}
.sp-bar{margin-top:44px;width:130px;height:3px;border-radius:99px;background:rgba(255,255,255,.1);overflow:hidden;animation:up .4s .6s both}
.sp-fill{height:100%;width:0;background:linear-gradient(90deg,#37d67a,#a8ffcb);border-radius:99px;animation:bf 2.6s .5s ease forwards}
@keyframes pop{from{transform:scale(.3);opacity:0}to{transform:scale(1);opacity:1}}
@keyframes up{from{opacity:0;transform:translateY(18px)}to{opacity:1;transform:translateY(0)}}
@keyframes bf{to{width:100%}}
#app{position:fixed;inset:0;overflow-y:auto;opacity:0;visibility:hidden;transition:opacity .7s,visibility .7s;background:radial-gradient(ellipse at 20% 10%,rgba(55,214,122,.09),transparent 50%),radial-gradient(ellipse at 80% 85%,rgba(55,214,122,.06),transparent 50%),linear-gradient(160deg,#050e0a,#081a0f 60%,#050e0a);background-attachment:fixed}
#app.show{opacity:1;visibility:visible}
.screen{display:none;min-height:100vh;padding:28px 16px;align-items:center;justify-content:center;flex-direction:column}
.screen.active{display:flex}
.lg-head{text-align:center;margin-bottom:28px}
.lg-logo{width:68px;height:68px;border-radius:20px;background:linear-gradient(145deg,#1a6b3a,#082010);border:1.5px solid rgba(55,214,122,.35);display:flex;align-items:center;justify-content:center;font-size:30px;margin:0 auto 14px}
.lg-title{font-size:28px;font-weight:900;color:#fff}.lg-title span{color:#37d67a}
.lg-sub{font-size:13px;color:rgba(255,255,255,.35);margin-top:5px}
.lg-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;width:100%;max-width:400px}
.lg-btn{padding:14px 8px;border-radius:16px;border:1.5px solid rgba(255,255,255,.09);background:rgba(255,255,255,.04);color:#fff;font-weight:700;font-size:13px;cursor:pointer;text-align:center;transition:.2s;font-family:'Nunito',sans-serif}
.lg-btn:hover,.lg-btn.sel{border-color:rgba(55,214,122,.5);background:rgba(55,214,122,.11);color:#37d67a;transform:translateY(-2px)}
.lg-native{display:block;font-size:17px;font-weight:900;margin-bottom:2px}
.lg-en{display:block;font-size:10px;color:rgba(255,255,255,.35)}
.lg-btn.sel .lg-en{color:rgba(55,214,122,.5)}
.lg-cont{margin-top:22px;width:100%;max-width:400px;padding:14px;border-radius:20px;border:none;background:linear-gradient(135deg,#37d67a,#2ea85f);color:#05220f;font-weight:900;font-size:16px;cursor:pointer;font-family:'Nunito',sans-serif;box-shadow:0 14px 35px rgba(55,214,122,.22);transition:.2s;display:none}
.lg-cont.show{display:block}
.lg-cont:hover{transform:translateY(-2px)}
.rl-cards{display:flex;gap:12px;width:100%;max-width:560px;justify-content:center;flex-wrap:wrap}
.rl-card{flex:1;padding:28px 14px;border-radius:24px;border:1.5px solid rgba(255,255,255,.09);background:linear-gradient(160deg,rgba(255,255,255,.07),rgba(255,255,255,.03));cursor:pointer;text-align:center;transition:.25s;position:relative;overflow:hidden}
.rl-card::after{content:"";position:absolute;inset:0;background:radial-gradient(circle at 50% 0%,rgba(55,214,122,.18),transparent 65%);opacity:0;transition:.3s}
.rl-card:hover::after{opacity:1}
.rl-card:hover{border-color:rgba(55,214,122,.45);transform:translateY(-5px);box-shadow:0 22px 55px rgba(55,214,122,.13)}
.rl-icon{font-size:54px;display:block;margin-bottom:12px}
.rl-name{font-size:20px;font-weight:900;color:#fff}
.rl-desc{font-size:12px;color:rgba(255,255,255,.4);margin-top:5px;line-height:1.5}
.rl-note{margin-top:12px;padding:9px 12px;border-radius:12px;background:rgba(55,214,122,.09);border:1px solid rgba(55,214,122,.2);font-size:11px;color:#37d67a;font-weight:800;line-height:1.5;white-space:pre-line}
.back-btn{margin-top:22px;padding:10px 22px;border-radius:13px;border:1px solid rgba(255,255,255,.1);background:rgba(255,255,255,.05);color:rgba(255,255,255,.45);font-size:13px;font-weight:800;cursor:pointer;font-family:'Nunito',sans-serif;transition:.2s}
.back-btn:hover{color:#fff}
.leaf{position:fixed;pointer-events:none;animation:lf linear infinite;opacity:0}
@keyframes lf{0%{transform:translateY(110vh) rotate(0deg);opacity:0}10%{opacity:.4}90%{opacity:.2}100%{transform:translateY(-80px) rotate(360deg);opacity:0}}
</style>
</head>
<body>
<script>['🌿','🌱','🍃','🌾','🍀','🌿','🌱'].forEach((l,i)=>{const d=document.createElement('div');d.className='leaf';d.textContent=l;d.style.cssText=`left:${Math.random()*100}%;font-size:${14+Math.random()*14}px;animation-duration:${12+Math.random()*10}s;animation-delay:${Math.random()*10}s`;document.body.appendChild(d);});</script>
<div id="splash"><div class="sp-logo"><img src="assets/images/logo.png" style="width:100%;height:100%;object-fit:cover;border-radius:32px"/></div><div class="sp-name">AgriConnect</div><div class="sp-tag">Farm to Shop Connect</div><div class="sp-bar"><div class="sp-fill"></div></div></div>
<div id="app">
  <div class="screen active" id="s-lang">
    <div class="lg-head"><div class="lg-logo"><img src="assets/images/logo.png" style="width:100%;height:100%;object-fit:cover;border-radius:16px"/></div><div class="lg-title">AGRI<span>CONNECT</span></div><div class="lg-sub">Select your language / మీ భాష ఎంచుకోండి</div></div>
    <div class="lg-grid" id="lgGrid"></div>
    <button class="lg-cont" id="lgCont" onclick="goRole()">Continue →</button>
  </div>
  <div class="screen" id="s-role">
    <div class="lg-head" style="margin-bottom:32px"><div class="lg-logo" style="margin-bottom:14px">🌿</div><div class="lg-title" id="rlTitle">Who are you?</div><div class="lg-sub" id="rlSub">Choose your role</div></div>
    <div class="rl-cards">
      <div class="rl-card" onclick="go('farmer')"><span class="rl-icon">👨‍🌾</span><div class="rl-name" id="rlF">Farmer</div><div class="rl-desc" id="rlFd">Buy fertilizers, seeds & more</div><div class="rl-note" id="rlFn">📌 Register & Login required</div></div>
      <div class="rl-card" onclick="go('delivery')"><span class="rl-icon">🛵</span><div class="rl-name">Delivery</div><div class="rl-desc">Deliver orders to farmers</div><div class="rl-note">📌 Register &amp; Login required</div></div>
      <div class="rl-card" onclick="go('shop')"><span class="rl-icon">🏪</span><div class="rl-name" id="rlS">Shop</div><div class="rl-desc" id="rlSd">Sell products & manage orders</div><div class="rl-note" id="rlSn">📌 Register & Login required</div></div>
    </div>
    <button class="back-btn" onclick="goBack()">← Back</button>
  </div>
</div>
<script>
const langs=[{c:'te',n:'తెలుగు',e:'Telugu'},{c:'hi',n:'हिंदी',e:'Hindi'},{c:'ta',n:'தமிழ்',e:'Tamil'},{c:'en',n:'English',e:'English'},{c:'kn',n:'ಕನ್ನಡ',e:'Kannada'},{c:'ml',n:'മലയാളം',e:'Malayalam'},{c:'mr',n:'मराठी',e:'Marathi'},{c:'gu',n:'ગુજરાતી',e:'Gujarati'},{c:'bn',n:'বাংলা',e:'Bengali'},{c:'pa',n:'ਪੰਜਾਬੀ',e:'Punjabi'},{c:'or',n:'ଓଡ଼ିଆ',e:'Odia'},{c:'as',n:'অসমীয়া',e:'Assamese'},{c:'ur',n:'اردو',e:'Urdu'},{c:'sa',n:'संस्कृत',e:'Sanskrit'},{c:'mai',n:'मैथिली',e:'Maithili'}];
const T={te:{who:'మీరు ఎవరు?',choose:'పాత్రను ఎంచుకోండి',f:'రైతు',s:'దుకాణం',fd:'ఎరువులు, విత్తనాలు కొనండి',sd:'ఉత్పత్తులు అమ్మండి',fn:'📌 నమోదు & లాగిన్ తప్పనిసరి',sn:'📌 నమోదు & లాగిన్ తప్పనిసరి'},hi:{who:'आप कौन हैं?',choose:'भूमिका चुनें',f:'किसान',s:'दुकान',fd:'उर्वरक, बीज खरीदें',sd:'उत्पाद बेचें',fn:'📌 पंजीकरण व लॉगिन जरूरी',sn:'📌 पंजीकरण व लॉगिन जरूरी'},ta:{who:'நீங்கள் யார்?',choose:'பங்கை தேர்ந்தெடுக்கவும்',f:'விவசாயி',s:'கடை',fd:'உரம், விதை வாங்கவும்',sd:'பொருட்கள் விற்கவும்',fn:'📌 பதிவு & உள்நுழைவு அவசியம்',sn:'📌 பதிவு & உள்நுழைவு அவசியம்'},en:{who:'Who are you?',choose:'Choose your role',f:'Farmer',s:'Shop',fd:'Buy fertilizers & seeds',sd:'Sell products & manage orders',fn:'📌 Register & Login required',sn:'📌 Register & Login required'}};
function t(l,k){return(T[l]||T.en)[k]||T.en[k]}
let sel=null;
const grid=document.getElementById('lgGrid');
langs.forEach(l=>{const b=document.createElement('button');b.className='lg-btn';b.innerHTML=`<span class="lg-native">${l.n}</span><span class="lg-en">${l.e}</span>`;b.onclick=()=>{document.querySelectorAll('.lg-btn').forEach(x=>x.classList.remove('sel'));b.classList.add('sel');sel=l.c;document.getElementById('lgCont').classList.add('show')};grid.appendChild(b);});
function goRole(){if(!sel)return;document.getElementById('s-lang').classList.remove('active');document.getElementById('s-role').classList.add('active');document.getElementById('rlTitle').textContent=t(sel,'who');document.getElementById('rlSub').textContent=t(sel,'choose');document.getElementById('rlF').textContent=t(sel,'f');document.getElementById('rlS').textContent=t(sel,'s');document.getElementById('rlFd').textContent=t(sel,'fd');document.getElementById('rlSd').textContent=t(sel,'sd');document.getElementById('rlFn').textContent=t(sel,'fn');document.getElementById('rlSn').textContent=t(sel,'sn');}
function go(r){window.location.href=r+'/login.php?lang='+sel}
function goBack(){document.getElementById('s-role').classList.remove('active');document.getElementById('s-lang').classList.add('active')}
window.addEventListener('load',()=>setTimeout(()=>{document.getElementById('splash').classList.add('hide');document.getElementById('app').classList.add('show');},3000));
</script>
</body></html>
