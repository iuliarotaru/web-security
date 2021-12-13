// ----------------------------------------------------------
// Comment form AJAX
  document.querySelectorAll(".comment .write_comment").forEach(element => element.style.display = 'none');
	document.querySelectorAll(".comment .reply_comment_btn").forEach(element => {
		element.onclick = event => {
			event.preventDefault();
			document.querySelectorAll(".comment .write_comment").forEach(element => element.style.display = 'none');
			document.querySelector("div[data-comment-id='" + element.getAttribute("data-comment-id") + "']").style.display = 'block';
			document.querySelector("div[data-comment-id='" + element.getAttribute("data-comment-id") + "'] input[name='name']").focus();
		};
	});
	// document.querySelectorAll(".comment .write_comment form").forEach(element => {
	// 	element.onsubmit = event => {
	// 		event.preventDefault();
	// 	 fetch('/comment', {
	// 			method: 'POST',
	// 			body: new FormData(element)
	// 		}).then(response => response.text()).then(data => {
	// 			element.parentElement.innerHTML = data;
	// 		});
	// 	};
	// });


const comment_form = document.querySelectorAll(".comment .write_comment form");
for (let i = 0; i < comment_form.length; i++) {
        comment_form[i].addEventListener('submit', async function(event) {
        event.preventDefault();
        console.log(event.target);
        // let current_post_id = event.target.querySelector("#post-id").value;
// //         console.log(current_post_id);
//         let current_post = document.getElementById(current_post_id);
        const formData = new FormData(comment_form[i]);
        console.log(formData);
        for(var pair of formData.entries()) {
            console.log(pair[0]+ ', '+ pair[1]);
         }
        let connection = await fetch('/comment', {
            method: "POST",
            body: formData,
          });
          if (!connection.ok) {
            let response = await connection.text();
            comment_form[i].insertAdjacentHTML("beforeend", response);
            console.log(response)
            return;
          } else {
            let response = await connection.text();
            console.log(response);
//             const comments = current_post.querySelector(".comments");
            event.target.innerHTML(response);

          }
          comment_form[i].reset();
    }
        )
}