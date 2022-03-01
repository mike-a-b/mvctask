<?php if ($exlog_test_results_data) : ?>

<div class="exlog-test-results-container">
  <h2>Important!</h2>
  <p>
    For ease of reading and to minimise the amount of personal data shared here, all values have been truncated to 10 characters.
  </p>

  <table>
      <thead>
          <tr>
              <?php foreach ($exlog_test_results_data[0] as $key => $value) :?>
                  <th><?php echo $key; ?></th>
              <?php endforeach; ?>
          </tr>
      </thead>
      <tbody>
          <?php foreach ($exlog_test_results_data as $user) :?>
              <tr>
              <?php foreach ($user as $key => $value) :?>
                  <td><?php echo substr($value, 0, 10); ?></td>
              <?php endforeach; ?>
              </tr>
          <?php endforeach; ?>
      </tbody>
  </table>
</div>

<?php else : ?>
    <?php throw new Exception("External Login Error: No test results found."); ?>
<?php endif; ?>
