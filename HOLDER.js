 // This is a delete code.
      $.when(
        $.getJSON(
          universityData.root_url +
            '/wp-json/wp/v2/posts?search=' +
            this.searchField.val()
        ),
        $.getJSON(
          universityData.root_url +
            '/wp-json/wp/v2/pages?search=' +
            this.searchField.val()
        )
      ).then(
        (posts, pages) => {
          // Combined results
          var combinedResults = posts[0].concat(pages[0]);
          this.resultsDiv.html(`
                    <h2 class="search-overlay__section-title">General Information</h2>
                    ${
                      combinedResults.length
                        ? '<ul class="link-list min-list">'
                        : '<p>No general information matches that search.</p>'
                    }
                        ${combinedResults
                          .map(
                            (item) =>
                              `<li><a href="${item.link}">${
                                item.title.rendered
                              }</a>${
                                item.type == 'post'
                                  ? ` by ${item.authorName}`
                                  : ''
                              }</li>`
                          )
                          .join('')}
                    ${combinedResults.length ? '</ul>' : ''}
                `);
          this.isSpinnerVisible = false;
        },
        () => {
          this.resultsDiv.html('<p>Unexpected error; Please try again.</p>');
        }
      );
