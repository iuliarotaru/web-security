// ----------------------------------------------------------
// Reply form AJAX

const reply_form = document.querySelectorAll(".reply_form");
for (let i = 0; i < reply_form.length; i++) {
        reply_form[i].addEventListener('submit', async function(event) {
        event.preventDefault();
        let current_post_id = event.target.querySelector("#post-id").value;
        console.log(current_post_id);
        let current_post = document.getElementById(current_post_id);
        const formData = new FormData(reply_form[i]);
        for(var pair of formData.entries()) {
            console.log(pair[0]+ ', '+ pair[1]);
         }
        let connection = await fetch('/reply', {
            method: "POST",
            body: formData,
          });
          if (!connection.ok) {
            let response = await connection.text();
            reply_form[i].insertAdjacentHTML("beforeend", response);
            console.log(response)
            return;
          } else {
            let response = await connection.text();
            console.log(response);
            const replies = current_post.querySelector(".replies");
            replies.insertAdjacentHTML(
              "afterend",
              `<div class="reply"> ${response}  </div>`
            );

          }
          reply_form[i].reset();
    })

}