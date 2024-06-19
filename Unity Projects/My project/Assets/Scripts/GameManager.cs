using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using TMPro;
using System.Linq; //Für das Select eingebunden
using UnityEngine.SceneManagement; //Für das Beenden und Wiederholen

public class GameManager : MonoBehaviour
{
    private Question[] _questions = null;
    public Question[] Questions {get {return _questions; } }

    [SerializeField] GameEvent events = null;

    [SerializeField] Animator timerAnimator = null;
    [SerializeField] TextMeshProUGUI timerText = null;
    [SerializeField] Color timerHalfWayOutColor = Color.yellow;
    [SerializeField] Color timerAlmostOutColor = Color.red;

    private List<AnswerData> PickedAnswers = new List<AnswerData>();
    private List<int> FinishedQuestions = new List<int>();
    private int currentQuestion = 0;
    private int timerStateParaHash = 0;
    private IEnumerator IE_WaitTillNextRound = null;
    private IEnumerator IE_StartTimer = null;
    private Color timerDefaultColor = Color.white;

    private bool isFinished{
        get{
            return (FinishedQuestions.Count < Questions.Length) ? false : true;
        }
    }

    void OnEnable (){
        events.UpdateQuestionAnswer += UpdateAnswers;
    }

    void OnDisable (){
        events.UpdateQuestionAnswer -= UpdateAnswers;
    }

    void Awake(){
        events.CurrentFinalScore = 0;
    }

    void Start(){
        events.StartupHighscore = PlayerPrefs.GetInt(GameUtility.SavePrefKey);

        timerDefaultColor = timerText.color;
        LoadQuestions();

        events.CurrentFinalScore = 0;
        timerStateParaHash = Animator.StringToHash("TimerState");

        var seed = UnityEngine.Random.Range(int.MinValue, int.MaxValue);
        UnityEngine.Random.InitState(seed);

        Display();
    }

    public void UpdateAnswers(AnswerData newAnswer){
        if(Questions[currentQuestion].GetAnswerType == Question.AnswerType.Single)
        {
            //Singlechoice-Block
            foreach(var answer in PickedAnswers)
            {
                if(answer != newAnswer)
                {
                    answer.Reset();
                }
                PickedAnswers.Clear();
                PickedAnswers.Add(newAnswer);
            }
        }
    }

    public void EraseAnswers(){
        PickedAnswers = new List<AnswerData>();
    }
    void Display(){
        EraseAnswers();
        var question = GetRandomQuestion();

        if(events.UpdateQuestionUI != null)
        {
            events.UpdateQuestionUI(question);
        }
        else
        {
            Debug.LogWarning("Ups! Something went wrong in GameManager.Display() method. UpdateQuestionUI() is empty.");
        }

        if(question.UseTimer)
        {
            UpdateTimer(question.UseTimer);
        }
    }

    public void Accept (){
        UpdateTimer(false);
        bool isCorrect = CheckAnswers();
        FinishedQuestions.Add(currentQuestion);

        UpdateScore((isCorrect) ? Questions[currentQuestion].AddScore : -Questions[currentQuestion].AddScore);

        if(isFinished)
        {
            SetHighscore();
        }

        var type = (isFinished) ? UIManager.ResolutionScreenType.Finish : (isCorrect) ? UIManager.ResolutionScreenType.Correct : UIManager.ResolutionScreenType.Incorrect;

        if(events.DisplayResolutionScreen != null)
        {
            events.DisplayResolutionScreen(type, Questions[currentQuestion].AddScore);
        }

        if(type != UIManager.ResolutionScreenType.Finish)
        {
            if(IE_WaitTillNextRound != null)
            {
                StopCoroutine(IE_WaitTillNextRound);
            }   
            IE_WaitTillNextRound = WaitTillNextRound();
            StartCoroutine(IE_WaitTillNextRound);
        }
    }

    void UpdateTimer(bool state){
        switch(state)
        {
            case true:
                IE_StartTimer = StartTimer();
                StartCoroutine(IE_StartTimer);

                timerAnimator.SetInteger(timerStateParaHash, 2);
                break;
            case false:
                if(IE_StartTimer != null)
                {
                    StopCoroutine(IE_StartTimer);
                }
                timerAnimator.SetInteger(timerStateParaHash, 1);
                break;
        }
    }

    IEnumerator StartTimer(){
        var totalTime = Questions[currentQuestion].Timer;
        var timeLeft = totalTime;

        timerText.color = timerDefaultColor;
        while(timeLeft > 0)
        {
            timeLeft--;

            if(timeLeft < totalTime/2 && timeLeft > totalTime/4)
            {
                timerText.color = timerHalfWayOutColor;
            }
            if(timeLeft < totalTime/4)
            {
                timerText.color = timerAlmostOutColor;
            }
            timerText.text = timeLeft.ToString();
            yield return new WaitForSeconds(1.0f);
        }
        Accept();
    }

    IEnumerator WaitTillNextRound (){
        yield return new WaitForSeconds(GameUtility.ResolutionDelayTime);
        Display();
    }

    Question GetRandomQuestion(){
        //Der Index der Frage wird von der Funktion GetRandomQuestionIndex()
        //zurückgegeben und mit dem wird dann die Frage herausgefunden und returned
        var randomIndex = GetRandomQuestionIndex();
        currentQuestion = randomIndex;

        return Questions[currentQuestion];
    }

    int GetRandomQuestionIndex(){
        var random = 0;

        if(FinishedQuestions.Count < Questions.Length)
        {
            do //Falls der Index in den Fragen enthalten ist und es nicht die gleiche Frage ist, wird die Schleife wieder ausgeführt
            //und eine weitere Frage wird verwendet.
            {
                random = UnityEngine.Random.Range(0, Questions.Length);
            }while(FinishedQuestions.Contains(random) || random == currentQuestion);
        }
        return random;
    }

    bool CheckAnswers (){
        if(!CompareAnswers())
        {
            return false;
        }
        return true;
    }

    bool CompareAnswers (){
        if(PickedAnswers.Count > 0)
        {
            //List für die korrekten Antworten
            List<int> c = Questions[currentQuestion].GetCorrectAnswers();
            //Liste der ausgewählten Antworten
            List<int> p = PickedAnswers.Select(x => x.AnswerIndex).ToList();

            var f = c.Except(p).ToList(); //Alles außer das in der Klammer
            var s = p.Except(c).ToList();

            return !f.Any() && !s.Any(); //wenn beide Listen was enthalten dann returnen wir
            //true ansonsten false 
        }
        return false;
    }

    void LoadQuestions(){
        Object[] objs = Resources.LoadAll("Questions", typeof(Question));
        _questions = new Question[objs.Length];
        for(int i = 0; i < objs.Length; i++)
        {
            _questions[i] = (Question)objs[i];
        }
    }

    public void RestartGame() //public cause we want to access it with an onClick-Event
    {
        SceneManager.LoadScene(SceneManager.GetActiveScene().buildIndex);
    }

    public void QuitGame(){
        Application.Quit();
    }

    private void SetHighscore(){
        var highscore = PlayerPrefs.GetInt(GameUtility.SavePrefKey);
        if(highscore < events.CurrentFinalScore)
        {
            PlayerPrefs.SetInt(GameUtility.SavePrefKey, events.CurrentFinalScore);
        }
    }

    private void UpdateScore (int add){
        events.CurrentFinalScore += add;

        if(events.ScoreUpdated != null)
        {
            events.ScoreUpdated();
        }
    }
}
