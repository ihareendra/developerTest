<?php

class RottenTomatoes
{
	
    var $apiKey = null;
    var $url = array();
    public function __construct( array $config )
    {
        $this->apiKey = $config['apikey'];
        $this->url = $config['url'];
    }
    public function search( $query )
    {
        $searchUrl = $this->url['search'] . '?apikey=' . $this->apiKey . '&q=' . urlencode( $query );
        return $this->call( $searchUrl );
    }
    protected function call( $url )
    {
        $session = curl_init( $url );
        curl_setopt( $session, CURLOPT_RETURNTRANSFER, true );
        $data = curl_exec( $session );
        curl_close( $session );
        return json_decode( $data );
    }
}
// Rotten Tomato configurations

$rottenTomatoesConfig = array(
    'apikey' => 'yfxz59zsaq3a5h3y85ej7mj6',
    'url' => array(
        'search' => 'http://api.rottentomatoes.com/api/public/v1.0/movies.json'
    )
);
// predefined search keywords

$keywords = array(
    $selectedColour = $_POST['Colour']
);
$rottenTomatoObj = new RottenTomatoes( $rottenTomatoesConfig );
$searchResults = array();
foreach( $keywords as $keyword ) {
    $searchResults[$keyword] = $rottenTomatoObj->search( $keyword );
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rotten Tomatoes</title>
    <link href="style.css" rel="stylesheet">
  </head>
  <body>

    <h1>Rotten Tomato Movie Search</h1><br>
    <h3>Please select a key word to be searched</h3>
    
    <form action="#" method="post"> <!--Creating the list which contains the key words-->
		<select name="Colour">
			<option value="red">Red</option>
			<option value="green">Green</option>
			<option value="blue">Blue</option>
			<option value="yellow">Yellow</option>
		</select>
		
		<input type="submit" name="submit" value="Get Selected Values" />
	</form>		
	
	
		<?php
			
		if(isset($_POST['submit'])){
			$selectedColour = $_POST['Colour'];  // Storing Selected Value In Variable
						}
		?>	
		<h3> <?php echo "You have selected :" .$selectedColour;  // Displaying Selected Value
			?> </h3>
			
		
	
        <?php foreach ( $searchResults as $keyword => $result ): ?>
            <?php if ( $result !== NULL ): ?>
                <h2>Search results for movies containing <em><?php echo $keyword; ?></em>.</h2>
                 <?php if ( $result->total > 0 ): ?> 
                    <div class="styledTable">
                        <table>
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Year</th>
                                    <th>Runtime</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach( $result->movies as $movie ):
                                    $movieTitle = str_ireplace ( $keyword , '<span class="extract">' . $keyword . '</span>', $movie->title );
                                ?>
                                <tr>
                                    <td><?php echo $movieTitle; ?></td>
                                    <td><?php echo $movie->year ?></td>
                                    <td><?php echo $movie->runtime ?> minutes</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p align="center">No results found</p>
                <?php endif; ?>

            <?php else: ?>
            <p>Error parsing json</p>
            <?php endif; ?>
        <?php endforeach; ?>
        
  </body>
</html>