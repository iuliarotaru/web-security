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
      console.log('iulia');
			document.querySelector("div[data-comment-id='" + event.target.getAttribute("data-comment-id") + "']").style.display = 'block';
			document.querySelector("div[data-comment-id='" + event.target.getAttribute("data-comment-id") + "'] input[name='name']").focus();
    };
  } );
  document.body.addEventListener( 'submit', function ( event ) {
    event.preventDefault();
    if( event.target.classList.contains ('comment_form') ) {
      let element = event.target;
       fetch('/comment', {
          method: 'POST',
          body: new FormData(element)
        }).then(response => response.text()).then(data => {
          element.parentElement.nextElementSibling.insertAdjacentHTML('beforeend', `
          <div class="comment">
                <p class="content"> ${element.elements['comment-text'].value}</p>
                <a class="reply_comment_btn" href="#" data-comment-id="${data}">Reply</a>
                  <div class="write_comment" data-comment-id="${data}">
                      <form class="comment_form">
                          <input name="parent-id" type="hidden" value="${data}">
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
        });
    }
  } );