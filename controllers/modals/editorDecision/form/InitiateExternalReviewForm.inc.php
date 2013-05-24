<?php

/**
 * @file controllers/modals/editorDecision/form/InitiateExternalReviewForm.inc.php
 *
 * Copyright (c) 2003-2013 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class InitiateReviewForm
 * @ingroup controllers_modal_editorDecision_form
 *
 * @brief Form for creating the first review round for a submission's external
 *  review (skipping internal)
 */

import('lib.pkp.classes.controllers.modals.editorDecision.form.EditorDecisionForm');

class InitiateExternalReviewForm extends EditorDecisionForm {

	/**
	 * Constructor.
	 * @param $submission Submission
	 * @param $decision int SUBMISSION_EDITOR_DECISION_...
	 * @param $stageId int WORKFLOW_STAGE_ID_...
	 */
	function InitiateExternalReviewForm($submission, $decision, $stageId) {
		parent::EditorDecisionForm($submission, $decision, $stageId, 'controllers/modals/editorDecision/form/initiateExternalReviewForm.tpl');
	}

	//
	// Implement protected template methods from Form
	//
	/**
	 * @see Form::execute()
	 */
	function execute($args, $request) {
		// Retrieve the submission.
		$submission = $this->getSubmission();

		// Record the decision.
		import('classes.workflow.EditorDecisionActionsManager');
		$actionLabels = EditorDecisionActionsManager::getActionLabels(array($this->_decision));

		import('lib.pkp.classes.submission.action.EditorAction');
		$editorAction = new EditorAction();
		$editorAction->recordDecision($request, $submission, $this->_decision, $actionLabels);

		// Move to the internal review stage.
		$editorAction->incrementWorkflowStage($submission, WORKFLOW_STAGE_ID_EXTERNAL_REVIEW, $request);

		// Create an initial internal review round.
		$this->_initiateReviewRound($submission, WORKFLOW_STAGE_ID_EXTERNAL_REVIEW, $request, REVIEW_ROUND_STATUS_PENDING_REVIEWERS);
	}
}

?>
