/usr/bin/indexer --merge in_pp_beers in_delta_pp_beers --rotate --config /etc/sphinxsearch/sphinx.conf >> /var/log/sphinxsearch/pp_merge_beers.log
/usr/bin/indexer --merge in_pp_beers_ascii in_delta_pp_beers_ascii --rotate --config /etc/sphinxsearch/sphinx.conf >> /var/log/sphinxsearch/pp_merge_beers_ascii.log
./update-sphinx-counter 1
/usr/bin/indexer --config /etc/sphinxsearch/sphinx.conf --rotate in_pp_beers >> /var/log/sphinxsearch/pp_beers.log
/usr/bin/indexer --config /etc/sphinxsearch/sphinx.conf --rotate in_pp_beers_ascii >> /var/log/sphinxsearch/pp_beers_ascii.log

/usr/bin/indexer --merge in_pp_beer_translations in_delta_pp_beer_translations --rotate --config /etc/sphinxsearch/sphinx.conf >> /var/log/sphinxsearch/pp_merge_beer_translations.log
/usr/bin/indexer --merge in_pp_beer_translations_ascii in_delta_pp_beer_translations_ascii --rotate --config /etc/sphinxsearch/sphinx.conf >> /var/log/sphinxsearch/pp_merge_beer_translations_ascii.log
./update-sphinx-counter 2
/usr/bin/indexer --config /etc/sphinxsearch/sphinx.conf --rotate in_pp_beer_translations >> /var/log/sphinxsearch/pp_beer_translations.log
/usr/bin/indexer --config /etc/sphinxsearch/sphinx.conf --rotate in_pp_beer_translations_ascii >> /var/log/sphinxsearch/pp_beer_translations_ascii.log
