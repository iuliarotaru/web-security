// ----------------------------------------------------------
// Comment form AJAX
  // document.querySelector(".comments").innerHTML = data;
  // document.querySelectorAll(".comments .write_comment").forEach(element => element.style.display = 'none');
  document.body.addEventListener( 'click', function ( event ) {
    if( event.target.classList.contains ('reply_comment_btn') ) {
      console.log('iulia');
      event.preventDefault();
			document.querySelectorAll(".comments .write_comment").forEach(element => element.style.display = 'none');
			document.querySelector("div[data-comment-id='" + event.target.getAttribute("data-comment-id") + "']").style.display = 'block';
			document.querySelector("div[data-comment-id='" + event.target.getAttribute("data-comment-id") + "'] input[name='name']").focus();
    };
  } );
	// document.querySelectorAll(".comments .reply_comment_btn").forEach(element => {
	// 	element.onclick = event => {
	// 		event.preventDefault();
	// 		document.querySelectorAll(".comments .write_comment").forEach(element => element.style.display = 'none');
	// 		document.querySelector("div[data-comment-id='" + element.getAttribute("data-comment-id") + "']").style.display = 'block';
	// 		document.querySelector("div[data-comment-id='" + element.getAttribute("data-comment-id") + "'] input[name='name']").focus();
	// 	};
	// });
	document.querySelectorAll(".comments .write_comment form").forEach(element => {
		element.onsubmit = event => {
			event.preventDefault();
		 fetch('/comment', {
				method: 'POST',
				body: new FormData(element)
			}).then(response => response.text()).then(data => {
        // console.log(element);
        // console.log(element.elements['comment-text'].value);
				element.parentElement.nextElementSibling.innerHTML = `
        <div class="comment">
              <p class="content"> ${element.elements['comment-text'].value}</p>
              <a class="reply_comment_btn" href="#" data-comment-id="${data}">Reply</a>
                <div class="write_comment" data-comment-id="${data}">
                    <form>
                        <input name="parent-id" type="hidden" value="${data}">
                        <input name="post-id" type="hidden" value="${element.elements['post-id'].value}">
                        <textarea name="comment-text" placeholder="Write your comment here..." required></textarea>
                        <button type="submit">Submit Comment</button>
                    </form>
                </div>
              <div class="replies">
              </div>
        </div>
        `
        element.style.display = "none";
        // console.log(data);
			});
		};
	});


// const comment_form = document.querySelectorAll(".comments .write_comment form");
// for (let i = 0; i < comment_form.length; i++) {
//         comment_form[i].addEventListener('submit', async function(event) {
//         event.preventDefault();
//         console.log(event.target.parentElement);
//         let current_element = event.target.parentElement;
//         let replies_sibling = current_element.nextElementSibling;
//         // let attribute = current_element.getAttribute('data-comment-id');
//         // let current_post_id = event.target.querySelector("#post-id").value;
// // //         console.log(current_post_id);
// //         let current_post = document.getElementById(current_post_id);
//         const formData = new FormData(comment_form[i]);
//         console.log(formData);
//         for(var pair of formData.entries()) {
//             console.log(pair[0]+ ', '+ pair[1]);
//          }
//         let connection = await fetch('/comment', {
//             method: "POST",
//             body: formData,
//           });
//           if (!connection.ok) {
//             let response = await connection.text();
//             comment_form[i].insertAdjacentHTML("beforeend", response);
//             console.log(response)
//             return;
//           } else {
//             let response = await connection.text();
//             console.log(response);
// //             const comments = current_post.querySelector(".comments");
//             replies_sibling.insertAdjacentHTML(
//               "afterbegin",

//             )
//             parentElement.insertAdjacentHTML(
//               "beforeend",
//               `<button id="dislike-button" class="icon icon-liked" onclick="dislikePost('${post_id}')"></button>`
//             );
//             space_to_add_comment.parentElement.innerHTML(response);

//           }
//           comment_form[i].reset();
//     }
//         )
// }