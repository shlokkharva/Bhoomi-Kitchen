  </main>
</div>
<script>
function toggleTheme(){const h=document.documentElement;const d=h.getAttribute('data-theme')==='dark';h.setAttribute('data-theme',d?'light':'dark');localStorage.setItem('theme',d?'light':'dark');}
(function(){const s=localStorage.getItem('theme')||'dark';document.documentElement.setAttribute('data-theme',s);})();
window.addEventListener('load',()=>{const l=document.getElementById('pageLoader');if(l){l.classList.add('hidden');setTimeout(()=>l.remove(),600);}});
function showToast(msg,type='success'){const c=document.getElementById('toastContainer');const t=document.createElement('div');t.className='toast toast-'+type;t.textContent=msg;c.appendChild(t);setTimeout(()=>t.remove(),4200);}
</script>
</body></html>
