<?php
require_once __DIR__ . '/bootstrap.php';

$user = current_user();
page_header('장면 만들기');
?>
<?php if (!$user): ?>
    <section class="panel">
        <h1>장면을 저장하려면 로그인이 필요합니다</h1>
        <p class="muted">비회원도 샘플 화면은 볼 수 있지만, 작품 저장과 공개/비공개 관리는 계정이 필요합니다.</p>
        <div class="actions">
            <a class="small-button primary" href="login.php?next=studio.php">로그인</a>
            <a class="small-button" href="signup.php">회원가입</a>
            <a class="small-button" href="index.php">비회원으로 샘플 작품만 보기</a>
        </div>
    </section>
<?php else: ?>
    <section class="panel">
        <h1>장면 만들기</h1>
        <p class="muted">작품, 캐릭터, 대사와 선택지를 간단히 저장합니다. 공개 상태는 나중에 내 창작물에서 바꿀 수 있어요.</p>
        <form class="form-grid" method="post" action="save-project.php">
            <label>작품 제목
                <input name="title" placeholder="별빛 도서관의 리아" required>
            </label>
            <label>장르
                <select name="genre" required>
                    <option value="">장르 선택</option>
                    <option value="장르 1">장르 1</option>
                    <option value="장르 2">장르 2</option>
                    <option value="장르 3">장르 3</option>
                </select>
            </label>
            <label>캐릭터 이름
                <input name="speaker" placeholder="리아" required>
            </label>
            <label>대사
                <textarea name="line" placeholder="이 책장 뒤에서 누군가 우리를 기다리고 있어." required></textarea>
            </label>
            <label>선택지 1
                <input name="choice_1" placeholder="책장 안쪽으로 들어간다" required>
            </label>
            <label>선택지 2
                <input name="choice_2" placeholder="사서에게 먼저 물어본다" required>
            </label>
            <label>공개 상태
                <select name="visibility" required>
                    <option value="private">비공개 초안</option>
                    <option value="public">공개 작품</option>
                    <option value="unlisted">링크 공개</option>
                </select>
            </label>
            <button class="small-button primary" type="submit">장면 저장하기</button>
        </form>
    </section>
<?php endif; ?>
<?php page_footer(); ?>
