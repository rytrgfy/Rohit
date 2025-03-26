
<?php
            // $fetch_academic_sql = "SELECT
            // academic_details.id AS academic_id,
            // academic_details.board,
            // boards.board_name,
            // academic_details.courses,
            // academic_details.total_marks,
            // academic_details.secured_marks,
            // academic_details.percentage,
            // academic_details.reference_file
            // FROM academic_details
            // JOIN boards ON academic_details.board = boards.id
            // WHERE academic_details.signup_id = $id";

            // // Execute the query
            // $result = $conn->query($fetch_academic_sql);

            // if (!$result) {
            //     die("Query failed: " . $conn->error);
            // }

            // // Fetch academic data
            // $academicData = [];
            // if ($result->num_rows > 0) {
            //     while ($row = $result->fetch_assoc()) {
            //         $academicData[] = $row;
            //     }
            // }

            ?>




            <!-- <h3>Academic Details</h3>
            <button type="button" id="add-academic">+</button>

            <div id="academic-container">
                <?php
                $index = 0;
                if (count($academicData) > 0):
                    foreach ($academicData as $data):
                        ?>
                        <div class="academic-section">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Board:</label>
                                    <select name="academic[<?php echo $index; ?>][board]" class="board-select">
                                        <option value="<?php echo $data['board']; ?>"><?php echo $data['board_name']; ?>
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Courses:</label>
                                    <input type="text" name="academic[<?php echo $index; ?>][courses]"
                                        value="<?php echo $data['courses']; ?>">
                                </div>

                                <div class="form-group">
                                    <label>Total Marks:</label>
                                    <input type="number" name="academic[<?php echo $index; ?>][total_marks]"
                                        value="<?php echo $data['total_marks']; ?>">
                                </div>

                                <div class="form-group">
                                    <label>Marks Secured:</label>
                                    <input type="number" name="academic[<?php echo $index; ?>][secured_marks]"
                                        value="<?php echo $data['secured_marks']; ?>">
                                </div>

                                <div class="form-group">
                                    <label>Percentage:</label>
                                    <input type="text" name="academic[<?php echo $index; ?>][percentage]" readonly
                                        value="<?php echo $data['percentage']; ?>">
                                </div>

                                <div class="form-group">
                                    <label>Reference Files:</label>
                                    <input type="file" name="reference_files[<?php echo $index; ?>][]" multiple>
                                    <?php if (!empty($data['reference_file'])): ?>
                                        <input type='hidden' name='prev_reference_files[<?php echo $index; ?>]'
                                            value='<?php echo htmlspecialchars($data['reference_file']); ?>'>
                                        <div class="reference-file-preview">
                                            <?php
                                            $files = explode(',', $data['reference_file']);
                                            foreach ($files as $file):
                                                $filePath = "file_uploads_data/" . htmlspecialchars($file);
                                                ?>
                                                <img src="<?php echo $filePath; ?>" alt="Reference File">
                                                <br><a href="<?php echo $filePath; ?>"
                                                    target="_blank"><?php echo htmlspecialchars($file); ?></a><br>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <p>No reference files uploaded.</p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <button type="button" class="remove-btn" onclick="removeAcademicRow(this)">Remove</button>
                        </div>
                        <?php
                        $index++;
                    endforeach;
                else:
                    echo "<p>No academic details available. Click + to add.</p>";
                endif;
                ?>
            </div> -->





            <h3>Academic Details</h3>
<button type="button" id="add-academic">+</button>

<div id="academic-details-container">
    <?php
    $academicIndex = 0;
    if (count($academicRecords) > 0):
        foreach ($academicRecords as $record):
            ?>
            <div class="academic-container">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Board:</label>
                        <select name="academic[<?php echo $academicIndex; ?>][boardId]" class="board-select" id="board-<?php echo $academicIndex; ?>">
                            <option value="<?php echo $record['boardId']; ?>"><?php echo $record['boardName']; ?></option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Courses:</label>
                        <input type="text" name="academic[<?php echo $academicIndex; ?>][courseName]"
                            value="<?php echo $record['courseName']; ?>">
                    </div>

                    <div class="form-group">
                        <label>Total Marks:</label>
                        <input type="number" name="academic[<?php echo $academicIndex; ?>][totalMarks]"
                            value="<?php echo $record['totalMarks']; ?>">
                    </div>

                    <div class="form-group">
                        <label>Marks Secured:</label>
                        <input type="number" name="academic[<?php echo $academicIndex; ?>][securedMarks]"
                            value="<?php echo $record['securedMarks']; ?>">
                    </div>

                    <div class="form-group">
                        <label>Percentage:</label>
                        <input type="text" name="academic[<?php echo $academicIndex; ?>][percentageScore]" readonly
                            value="<?php echo $record['percentageScore']; ?>">
                    </div>

                    <div class="form-group">
                        <label>Reference Files:</label>
                        <input type="file" name="referenceFiles[<?php echo $academicIndex; ?>][]" multiple>
                    </div>
                </div>

                <button type="button" class="remove-btn" onclick="removeAcademicRow(this)">Remove</button>
            </div>
            <?php
            $academicIndex++;
        endforeach;
    else:
        echo "<p>No academic details available. Click + to add.</p>";
    endif;
    ?>
</div>
