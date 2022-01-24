// ----------------------------------------------------------
// Comment form AJAX
  document.body.addEventListener( 'click', function ( event ) {
    if( event.target.classList.contains ('reply_comment_btn') ) {
      event.preventDefault();
			document.querySelectorAll(".comments .write_comment").forEach((element) => {
        if (element.getAttribute('data-comment-id') != -1) {
          element.style.display = 'none';
        }
        });
			document.querySelector("div[data-comment-id='" + event.target.getAttribute("data-comment-id") + "']").style.display = 'block';
			document.querySelector("div[data-comment-id='" + event.target.getAttribute("data-comment-id") + "'] input[name='name']").focus();
    };
  } );
  document.body.addEventListener( 'submit', async function ( event ) {
    event.preventDefault();
    if( event.target.classList.contains ('comment_form') ) {
      let element = event.target;
       let connection = await fetch('/comment', {
         method: 'POST',
         body: new FormData(element)
       })
       if (!connection.ok) {
         console.log(connection);
         let response = await connection.text();
         element.insertAdjacentHTML("beforeend", response);
         return;
       } else {
        let response = await connection.text();
        element.parentElement.nextElementSibling.insertAdjacentHTML('beforeend', `
            <div class="comment">
                  <p class="content"> ${element.elements['comment-text'].value}</p>
                  <a class="reply_comment_btn" href="#" data-comment-id="${response}">Reply</a>
                    <div class="write_comment" data-comment-id="${response}">
                        <form class="comment_form">
                            <input name="csrf" type="hidden" value="${element.elements['csrf'].value}">
                            <input name="parent-id" type="hidden" value="${response}">
                            <input name="post-id" type="hidden" value="${element.elements['post-id'].value}">
                            <textarea name="comment-text" placeholder="Write your comment here..." required></textarea>
                            <button type="submit">Submit Comment</button>
                        </form>
                    </div>
                  <div class="replies">
                  </div>
            </div>
            ` )
            element.parentElement.style.display = "none";
            element.reset();
       }
    }
  } );