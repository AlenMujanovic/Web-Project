function getBookmarks() {
    fetch(BASE + "api/bookmarks", { credentials: "include" })
        .then((result) => result.json())
        .then((data) => {
            displayBookmarks(data.bookmarks, data.usernameFetch);
        });
}

function addBookmark(auctionId) {
    fetch(BASE + "api/bookmarks/add/" + auctionId, { credentials: "include" })
        .then((result) => result.json())
        .then((data) => {
            if (data.error === 0) {
                getBookmarks();
            }
        });
}

function clearBookmarks() {
    fetch(BASE + "api/bookmarks/clear", { credentials: "include" })
        .then((result) => result.json())
        .then((data) => {
            if (data.error === 0) {
                getBookmarks();
            }
        });
}

function displayBookmarks(bookmarks, usernameFetch) {
    const usernameDiv = document.querySelector(".userFetch");
    usernameDiv.innerHTML = "";

    if (!usernameFetch) {
        usernameDiv.innerHTML = "You are loged out!";
    } else {
        usernameDiv.innerHTML = "Welcome " + usernameFetch + "!";
    }

    const bookmarksDiv = document.querySelector(".bookmarks");
    bookmarksDiv.innerHTML = "";

    if (bookmarks.length === 0) {
        bookmarksDiv.innerHTML = "No bookmarks!";
        return;
    }

    for (bookmark of bookmarks) {
        const bookmarkLink = document.createElement("a");
        bookmarkLink.style.display = "block";
        bookmarkLink.innerHTML = bookmark.title;
        bookmarkLink.href = BASE + "auction/" + bookmark.auction_id;

        bookmarksDiv.appendChild(bookmarkLink);
    }
}

addEventListener("load", getBookmarks);
